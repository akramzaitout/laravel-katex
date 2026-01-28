<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex\Compilers;

use AkramZaitout\LaravelKatex\Services\KatexRenderer;

/**
 * KaTeX Blade Compiler
 * 
 * Compiles Blade directives into PHP code for rendering KaTeX mathematical expressions.
 * Handles all Blade directive compilation logic.
 * 
 * @package AkramZaitout\LaravelKatex\Compilers
 */
class KatexBladeCompiler
{
    /**
     * KaTeX renderer instance.
     *
     * @var KatexRenderer
     */
    protected KatexRenderer $renderer;

    /**
     * Create a new KatexBladeCompiler instance.
     *
     * @param KatexRenderer $renderer
     */
    public function __construct(KatexRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Compile the @katexStyles directive.
     * 
     * Generates PHP code that outputs the KaTeX CSS stylesheet link tag.
     *
     * @return string
     */
    public function compileStyles(): string
    {
        return <<<'PHP'
<?php echo app(\AkramZaitout\LaravelKatex\Services\KatexRenderer::class)->generateStylesheet(); ?>
PHP;
    }

    /**
     * Compile the @katexScripts directive.
     * 
     * Generates PHP code that outputs KaTeX JavaScript tags with optional configuration.
     *
     * @param string|null $expression Optional configuration array as string
     * @return string
     */
    public function compileScripts(?string $expression = null): string
    {
        $options = $expression ?: '[]';

        return <<<PHP
<?php echo app(\AkramZaitout\LaravelKatex\Services\KatexRenderer::class)->generateScripts({$options}); ?>
PHP;
    }

    /**
     * Compile the @katex directive for inline math.
     * 
     * Generates PHP code that wraps the expression in inline math delimiters.
     *
     * @param string $expression The math expression to render
     * @return string
     */
    public function compileInlineMath(string $expression): string
    {
        return "<?php echo app(\\AkramZaitout\\LaravelKatex\\Services\\KatexRenderer::class)->wrapInline(e({$expression})); ?>";
    }

    /**
     * Compile the @katexBlock directive for display math.
     * 
     * Generates PHP code that wraps the expression in display math delimiters.
     *
     * @param string $expression The math expression to render
     * @return string
     */
    public function compileBlockMath(string $expression): string
    {
        return "<?php echo app(\\AkramZaitout\\LaravelKatex\\Services\\KatexRenderer::class)->wrapDisplay(e({$expression})); ?>";
    }

    /**
     * Compile the @katexComponent directive.
     * 
     * Generates PHP code for rendering a Blade component.
     *
     * @param string $expression Component parameters
     * @return string
     */
    public function compileComponent(string $expression): string
    {
        return "<?php echo \$__env->make('katex::components.math', {$expression}, \\Illuminate\\Support\\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>";
    }
}