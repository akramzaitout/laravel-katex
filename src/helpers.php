<?php

declare(strict_types=1);

if (!function_exists('katex')) {
    /**
     * Get the KaTeX renderer instance or render an expression.
     *
     * @param string|null $expression
     * @param bool $display
     * @return \AkramZaitout\LaravelKatex\Services\KatexRenderer|string
     */
    function katex(?string $expression = null, bool $display = false)
    {
        $renderer = app(\AkramZaitout\LaravelKatex\Services\KatexRenderer::class);

        if ($expression === null) {
            return $renderer;
        }

        return $display 
            ? $renderer->wrapDisplay($expression)
            : $renderer->wrapInline($expression);
    }
}

if (!function_exists('katex_inline')) {
    /**
     * Render an inline math expression.
     *
     * @param string $expression
     * @return string
     */
    function katex_inline(string $expression): string
    {
        return app(\AkramZaitout\LaravelKatex\Services\KatexRenderer::class)->wrapInline($expression);
    }
}

if (!function_exists('katex_display')) {
    /**
     * Render a display math expression.
     *
     * @param string $expression
     * @return string
     */
    function katex_display(string $expression): string
    {
        return app(\AkramZaitout\LaravelKatex\Services\KatexRenderer::class)->wrapDisplay($expression);
    }
}

if (!function_exists('katex_styles')) {
    /**
     * Generate KaTeX stylesheet link tag.
     *
     * @return string
     */
    function katex_styles(): string
    {
        return app(\AkramZaitout\LaravelKatex\Services\KatexRenderer::class)->generateStylesheet();
    }
}

if (!function_exists('katex_scripts')) {
    /**
     * Generate KaTeX script tags.
     *
     * @param array $options
     * @return string
     */
    function katex_scripts(array $options = []): string
    {
        return app(\AkramZaitout\LaravelKatex\Services\KatexRenderer::class)->generateScripts($options);
    }
}