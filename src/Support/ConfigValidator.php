<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex\Support;

/**
 * Configuration Validator
 *
 * Validates KaTeX configuration arrays to ensure all required values
 * are present and properly formatted.
 */
class ConfigValidator
{
    /**
     * Validation errors.
     *
     * @var array<int, string>
     */
    protected array $errors = [];

    /**
     * Required configuration keys.
     *
     * @var array<int, string>
     */
    protected array $requiredKeys = [
        'version',
        'cdn',
        'options',
    ];

    /**
     * Validate configuration array.
     *
     * @param  array<string, mixed>  $config
     */
    public function validate(array $config): bool
    {
        $this->errors = [];

        $this->validateRequiredKeys($config);
        $this->validateVersion($config);
        $this->validateCdn($config);
        $this->validateOptions($config);

        return empty($this->errors);
    }

    /**
     * Validate required keys are present.
     *
     * @param  array<string, mixed>  $config
     */
    protected function validateRequiredKeys(array $config): void
    {
        foreach ($this->requiredKeys as $key) {
            if (! array_key_exists($key, $config)) {
                $this->errors[] = sprintf('Missing required configuration key: %s', $key);
            }
        }
    }

    /**
     * Validate version format.
     *
     * @param  array<string, mixed>  $config
     */
    protected function validateVersion(array $config): void
    {
        if (! isset($config['version'])) {
            return;
        }

        if (! is_string($config['version'])) {
            $this->errors[] = 'Version must be a string';

            return;
        }

        // Validate semantic versioning format
        if (! preg_match('/^\d+\.\d+\.\d+$/', $config['version'])) {
            $this->errors[] = 'Version must follow semantic versioning (e.g., 0.16.28)';
        }
    }

    /**
     * Validate CDN URL format.
     *
     * @param  array<string, mixed>  $config
     */
    protected function validateCdn(array $config): void
    {
        if (! isset($config['cdn'])) {
            return;
        }

        if (! is_string($config['cdn'])) {
            $this->errors[] = 'CDN must be a string';

            return;
        }

        if (! filter_var($config['cdn'], FILTER_VALIDATE_URL)) {
            $this->errors[] = 'CDN must be a valid URL';
        }
    }

    /**
     * Validate options array.
     *
     * @param  array<string, mixed>  $config
     */
    protected function validateOptions(array $config): void
    {
        if (! isset($config['options'])) {
            return;
        }

        if (! is_array($config['options'])) {
            $this->errors[] = 'Options must be an array';

            return;
        }

        $this->validateDelimiters($config['options']);
    }

    /**
     * Validate delimiters configuration.
     *
     * @param  array<string, mixed>  $options
     */
    protected function validateDelimiters(array $options): void
    {
        if (! isset($options['delimiters'])) {
            return;
        }

        if (! is_array($options['delimiters'])) {
            $this->errors[] = 'Delimiters must be an array';

            return;
        }

        foreach ($options['delimiters'] as $index => $delimiter) {
            if (! is_array($delimiter)) {
                $this->errors[] = sprintf('Delimiter at index %d must be an array', $index);

                continue;
            }

            $this->validateDelimiterStructure($delimiter, $index);
        }
    }

    /**
     * Validate individual delimiter structure.
     *
     * @param  array<string, mixed>  $delimiter
     */
    protected function validateDelimiterStructure(array $delimiter, int $index): void
    {
        $requiredDelimiterKeys = ['left', 'right', 'display'];

        foreach ($requiredDelimiterKeys as $key) {
            if (! array_key_exists($key, $delimiter)) {
                $this->errors[] = sprintf('Delimiter at index %d missing required key: %s', $index, $key);
            }
        }

        if (isset($delimiter['left']) && ! is_string($delimiter['left'])) {
            $this->errors[] = sprintf('Delimiter at index %d: left must be a string', $index);
        }

        if (isset($delimiter['right']) && ! is_string($delimiter['right'])) {
            $this->errors[] = sprintf('Delimiter at index %d: right must be a string', $index);
        }

        if (isset($delimiter['display']) && ! is_bool($delimiter['display'])) {
            $this->errors[] = sprintf('Delimiter at index %d: display must be a boolean', $index);
        }
    }

    /**
     * Get validation errors.
     *
     * @return array<int, string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if there are any validation errors.
     */
    public function hasErrors(): bool
    {
        return ! empty($this->errors);
    }
}
