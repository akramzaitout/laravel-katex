<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * KaTeX Facade
 *
 * @method static string generateStylesheet()
 * @method static string generateScripts(array $options = [])
 * @method static string wrapInline(string $expression)
 * @method static string wrapDisplay(string $expression)
 * @method static mixed getConfig(string $key, mixed $default = null)
 * @method static bool isConfigured()
 * @method static array getConfigErrors()
 *
 * @see \AkramZaitout\LaravelKatex\Services\KatexRenderer
 */
class Katex extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'katex';
    }
}
