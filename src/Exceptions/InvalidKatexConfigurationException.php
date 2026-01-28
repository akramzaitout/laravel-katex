<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex\Exceptions;

use Exception;

/**
 * Invalid KaTeX Configuration Exception
 *
 * Thrown when the KaTeX configuration is invalid or incomplete.
 */
class InvalidKatexConfigurationException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $message = 'Invalid KaTeX configuration', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
