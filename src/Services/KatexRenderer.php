<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex\Services;

use AkramZaitout\LaravelKatex\Exceptions\InvalidKatexConfigurationException;
use AkramZaitout\LaravelKatex\Support\ConfigValidator;

/**
 * KaTeX Renderer Service
 *
 * Core service for rendering KaTeX mathematical expressions.
 * Handles configuration, asset generation, and math expression formatting.
 */
class KatexRenderer
{
    /**
     * Package configuration.
     *
     * @var array<string, mixed>
     */
    protected $config;

    /**
     * Configuration validator.
     */
    protected $validator;

    /**
     * Create a new KatexRenderer instance.
     *
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidKatexConfigurationException
     */
    public function __construct(array $config)
    {
        $this->validator = new ConfigValidator;
        $this->setConfig($config);
    }

    /**
     * Set and validate configuration.
     *
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidKatexConfigurationException
     */
    protected function setConfig(array $config): void
    {
        if (! $this->validator->validate($config)) {
            throw new InvalidKatexConfigurationException(
                'Invalid KaTeX configuration: ' . implode(', ', $this->validator->getErrors())
            );
        }

        $this->config = $config;
    }

    /**
     * Get configuration value.
     */
    public function getConfig(string $key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }

    /**
     * Generate the KaTeX stylesheet link tag.
     */
    public function generateStylesheet(): string
    {
        $version = $this->getConfig('version', '0.16.28');
        $cdn = $this->getConfig('cdn', 'https://cdn.jsdelivr.net/npm/katex');
        $integrity = $this->getConfig('css_integrity', '');

        $attributes = [
            'rel' => 'stylesheet',
            'href' => sprintf('%s@%s/dist/katex.min.css', e($cdn), e($version)),
            'crossorigin' => 'anonymous',
        ];
        if ($this->getConfig('use_local_assets', false)) {
            $attributes = [
                'rel' => 'stylesheet',
                'href' => asset('vendor/katex/katex.min.css'),
            ];
        } else {
            $attributes = [
                'rel' => 'stylesheet',
                'href' => sprintf('%s@%s/dist/katex.min.css', e($cdn), e($version)),
                'crossorigin' => 'anonymous',
            ];
        }
        if (! empty($integrity)) {
            $attributes['integrity'] = e($integrity);
        }

        return $this->buildHtmlTag('link', $attributes);
    }

    /**
     * Generate the KaTeX script tags.
     *
     * @param  array<string, mixed>  $options
     */
    public function generateScripts(array $options = []): string
    {
        $version = $this->getConfig('version', '0.16.28');
        $cdn = $this->getConfig('cdn', 'https://cdn.jsdelivr.net/npm/katex');
        $jsIntegrity = $this->getConfig('js_integrity', '');
        $autoRenderIntegrity = $this->getConfig('auto_render_integrity', '');

        $finalOptions = $this->mergeOptions($options);
        $jsonOptions = $this->encodeOptions($finalOptions);

        $scripts = [];

        // KaTeX core script
        $scripts[] = $this->buildScriptTag(
            sprintf('%s@%s/dist/katex.min.js', $cdn, $version),
            $jsIntegrity
        );
        if ($this->getConfig('use_local_assets', false)) {
            $scripts[] = $this->buildScriptTag(asset('vendor/katex/katex.min.js'));
        } else {
            $scripts[] = $this->buildScriptTag(
                sprintf('%s@%s/dist/katex.min.js', $cdn, $version),
                $jsIntegrity
            );
        }
        // Auto-render extension with onload callback
        $onload = sprintf('renderMathInElement(document.body, %s);', $jsonOptions);
        $scripts[] = $this->buildScriptTag(
            sprintf('%s@%s/dist/contrib/auto-render.min.js', $cdn, $version),
            $autoRenderIntegrity,
            $onload
        );
        
        if ($this->getConfig('use_local_assets', false)) {
            $scripts[] = $this->buildScriptTag(
                asset('vendor/katex/contrib/auto-render.min.js'),
                '',
                $onload
            );
        } else {
            $scripts[] = $this->buildScriptTag(
                sprintf('%s@%s/dist/contrib/auto-render.min.js', $cdn, $version),
                $autoRenderIntegrity,
                $onload
            );
        }
        return implode("\n", $scripts);
    }

    /**
     * Wrap expression in inline math delimiters.
     */
    public function wrapInline(string $expression): string
    {
        $left = $this->getConfig('delimiters.inline.left', '\\(');
        $right = $this->getConfig('delimiters.inline.right', '\\)');

        return sprintf('%s%s%s', $left, $expression, $right);
    }

    /**
     * Wrap expression in display math delimiters.
     */
    public function wrapDisplay(string $expression): string
    {
        $left = $this->getConfig('delimiters.display.left', '$$');
        $right = $this->getConfig('delimiters.display.right', '$$');

        return sprintf('%s%s%s', $left, $expression, $right);
    }

    /**
     * Merge user options with default options.
     *
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    protected function mergeOptions(array $options): array
    {
        $defaultOptions = $this->getConfig('options', []);

        return array_replace_recursive($defaultOptions, $options);
    }

    /**
     * Encode options as JSON for JavaScript.
     *
     * @param  array<string, mixed>  $options
     */
    public function encodeOptions(array $options): string
    {
        $json = json_encode(
            $options,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('JSON encoding error: ' . json_last_error_msg());
        }

        return $json;
    }

    /**
     * Build an HTML tag with attributes.
     *
     * @param  array<string, string>  $attributes
     */
    protected function buildHtmlTag(string $tag, array $attributes, bool $selfClosing = true): string
    {
        $attributeString = $this->buildAttributes($attributes);
        $closing = $selfClosing ? ' /' : '';

        return sprintf('<%s%s%s>', $tag, $attributeString, $closing);
    }

    /**
     * Build a script tag.
     */
    protected function buildScriptTag(string $src, string $integrity = '', ?string $onload = null): string
    {
        $attributes = [
            'defer' => null,
            'src' => e($src),
            'crossorigin' => 'anonymous',
        ];

        if (! empty($integrity)) {
            $attributes['integrity'] = e($integrity);
        }

        if ($onload !== null) {
            $attributes['onload'] = e($onload);
        }

        $attributeString = $this->buildAttributes($attributes);

        return sprintf('<script%s></script>', $attributeString);
    }

    /**
     * Build HTML attribute string.
     *
     * @param  array<string, string|null>  $attributes
     */
    protected function buildAttributes(array $attributes): string
    {
        $parts = [];

        foreach ($attributes as $key => $value) {
            if ($value === null) {
                $parts[] = e($key);
            } else {
                $parts[] = sprintf('%s="%s"', e($key), $value);
            }
        }

        return empty($parts) ? '' : ' ' . implode(' ', $parts);
    }

    /**
     * Check if the renderer is configured correctly.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->config) && $this->validator->validate($this->config);
    }

    /**
     * Get all configuration errors.
     *
     * @return array<int, string>
     */
    public function getConfigErrors(): array
    {
        return $this->validator->getErrors();
    }
}
