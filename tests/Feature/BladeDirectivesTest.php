<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex\Tests\Feature;

use AkramZaitout\LaravelKatex\KatexServiceProvider;
use AkramZaitout\LaravelKatex\Services\KatexRenderer;
use Illuminate\Support\Facades\Blade;
use Orchestra\Testbench\TestCase;

/**
 * Feature tests for Blade directives.
 */
class BladeDirectivesTest extends TestCase
{
    /**
     * Render a Blade string.
     */
    protected function renderBlade(string $string, array $data = []): string
    {
        if (method_exists(Blade::class, 'render')) {
            return Blade::render($string, $data);
        }

        $php = Blade::compileString($string);
        $obLevel = ob_get_level();
        ob_start();
        extract($data);
        try {
            eval('?>' . $php);
        } catch (\Throwable $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }
            throw $e;
        }

        return ob_get_clean();
    }

    protected function getPackageProviders($app): array
    {
        return [
            KatexServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('katex.version', '0.16.28');
        $app['config']->set('katex.cdn', 'https://cdn.jsdelivr.net/npm/katex');
        $app['config']->set('katex.options', [
            'delimiters' => [
                ['left' => '$$', 'right' => '$$', 'display' => true],
                ['left' => '\\(', 'right' => '\\)', 'display' => false],
            ],
            'throwOnError' => false,
        ]);
    }

    public function test_katex_styles_directive_renders(): void
    {
        $output = $this->renderBlade('@katexStyles');

        $this->assertStringContainsString('<link', $output);
        $this->assertStringContainsString('rel="stylesheet"', $output);
        $this->assertStringContainsString('katex.min.css', $output);
    }

    public function test_katex_scripts_directive_renders(): void
    {
        $output = $this->renderBlade('@katexScripts');

        $this->assertStringContainsString('<script', $output);
        $this->assertStringContainsString('katex.min.js', $output);
        $this->assertStringContainsString('auto-render.min.js', $output);
    }

    public function test_katex_scripts_directive_accepts_options(): void
    {
        $output = $this->renderBlade("@katexScripts(['throwOnError' => true])");

        $this->assertStringContainsString('&quot;throwOnError&quot;:true', $output);
    }

    public function test_katex_inline_directive_renders(): void
    {
        $output = $this->renderBlade("@katex('x^2')");

        $this->assertStringContainsString('\\(', $output);
        $this->assertStringContainsString('x^2', $output);
        $this->assertStringContainsString('\\)', $output);
    }

    public function test_katex_block_directive_renders(): void
    {
        $output = $this->renderBlade("@katexBlock('\\sum_{i=1}^{n}')");

        $this->assertStringContainsString('$$', $output);
        $this->assertStringContainsString('\\sum_{i=1}^{n}', $output);
    }

    public function test_katex_directive_escapes_html(): void
    {
        $output = $this->renderBlade("@katex('<script>alert(1)</script>')");

        $this->assertStringNotContainsString('<script>', $output);
        $this->assertStringContainsString('&lt;script&gt;', $output);
    }

    public function test_katex_renderer_is_bound_in_container(): void
    {
        $renderer = $this->app->make(KatexRenderer::class);

        $this->assertInstanceOf(KatexRenderer::class, $renderer);
    }

    public function test_katex_facade_works(): void
    {
        $this->app['config']->set('app.aliases.Katex', \AkramZaitout\LaravelKatex\Facades\Katex::class);

        $output = \AkramZaitout\LaravelKatex\Facades\Katex::wrapInline('E=mc^2');

        $this->assertStringContainsString('\\(', $output);
        $this->assertStringContainsString('E=mc^2', $output);
        $this->assertStringContainsString('\\)', $output);
    }

    public function test_helper_functions_work(): void
    {
        require_once __DIR__ . '/../../src/helpers.php';

        $inline = katex_inline('a^2 + b^2');
        $this->assertStringContainsString('\\(', $inline);
        $this->assertStringContainsString('a^2 + b^2', $inline);

        $display = katex_display('\\int_{0}^{\\infty}');
        $this->assertStringContainsString('$$', $display);
        $this->assertStringContainsString('\\int_{0}^{\\infty}', $display);

        $styles = katex_styles();
        $this->assertStringContainsString('<link', $styles);

        $scripts = katex_scripts();
        $this->assertStringContainsString('<script', $scripts);
    }
}
