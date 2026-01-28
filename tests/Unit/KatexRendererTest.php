<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex\Tests\Unit;

use AkramZaitout\LaravelKatex\Exceptions\InvalidKatexConfigurationException;
use AkramZaitout\LaravelKatex\Services\KatexRenderer;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for KatexRenderer service.
 */
class KatexRendererTest extends TestCase
{
    protected KatexRenderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->renderer = new KatexRenderer([
            'version' => '0.16.28',
            'cdn' => 'https://cdn.jsdelivr.net/npm/katex',
            'css_integrity' => 'sha384-test',
            'js_integrity' => 'sha384-test',
            'auto_render_integrity' => 'sha384-test',
            'options' => [
                'delimiters' => [
                    ['left' => '$$', 'right' => '$$', 'display' => true],
                    ['left' => '\\(', 'right' => '\\)', 'display' => false],
                ],
                'throwOnError' => false,
            ],
        ]);
    }

    public function test_it_generates_stylesheet_link(): void
    {
        $stylesheet = $this->renderer->generateStylesheet();

        $this->assertStringContainsString('<link', $stylesheet);
        $this->assertStringContainsString('rel="stylesheet"', $stylesheet);
        $this->assertStringContainsString('katex@0.16.28/dist/katex.min.css', $stylesheet);
        $this->assertStringContainsString('integrity="sha384-test"', $stylesheet);
        $this->assertStringContainsString('crossorigin="anonymous"', $stylesheet);
    }

    public function test_it_generates_script_tags(): void
    {
        $scripts = $this->renderer->generateScripts();

        $this->assertStringContainsString('<script', $scripts);
        $this->assertStringContainsString('katex@0.16.28/dist/katex.min.js', $scripts);
        $this->assertStringContainsString('auto-render.min.js', $scripts);
        $this->assertStringContainsString('renderMathInElement', $scripts);
    }

    public function test_it_wraps_inline_expression(): void
    {
        $wrapped = $this->renderer->wrapInline('x^2 + y^2 = z^2');

        $this->assertStringStartsWith('\\(', $wrapped);
        $this->assertStringEndsWith('\\)', $wrapped);
        $this->assertStringContainsString('x^2 + y^2 = z^2', $wrapped);
    }

    public function test_it_wraps_display_expression(): void
    {
        $wrapped = $this->renderer->wrapDisplay('\\sum_{i=1}^{n} i');

        $this->assertStringStartsWith('$$', $wrapped);
        $this->assertStringEndsWith('$$', $wrapped);
        $this->assertStringContainsString('\\sum_{i=1}^{n} i', $wrapped);
    }

    public function test_it_merges_options_correctly(): void
    {
        $scripts = $this->renderer->generateScripts([
            'throwOnError' => true,
            'errorColor' => '#ff0000',
        ]);

        $this->assertStringContainsString('"throwOnError":true', $scripts);
        $this->assertStringContainsString('"errorColor":"#ff0000"', $scripts);
    }

    public function test_it_gets_config_values(): void
    {
        $version = $this->renderer->getConfig('version');
        $this->assertEquals('0.16.28', $version);

        $nonExistent = $this->renderer->getConfig('non_existent', 'default');
        $this->assertEquals('default', $nonExistent);
    }

    public function test_it_validates_configuration(): void
    {
        $this->assertTrue($this->renderer->isConfigured());
        $this->assertEmpty($this->renderer->getConfigErrors());
    }

    public function test_it_throws_exception_for_invalid_config(): void
    {
        $this->expectException(InvalidKatexConfigurationException::class);

        new KatexRenderer([
            'version' => 123, // Invalid: should be string
        ]);
    }

    public function test_it_escapes_html_in_output(): void
    {
        $renderer = new KatexRenderer([
            'version' => '0.16.28',
            'cdn' => 'https://example.com"><script>alert(1)</script><a href="',
            'options' => [
                'delimiters' => [],
            ],
        ]);

        $stylesheet = $renderer->generateStylesheet();
        $this->assertStringNotContainsString('<script>', $stylesheet);
        $this->assertStringContainsString('&quot;', $stylesheet);
    }

    public function test_it_handles_missing_integrity_hashes(): void
    {
        $renderer = new KatexRenderer([
            'version' => '0.16.28',
            'cdn' => 'https://cdn.jsdelivr.net/npm/katex',
            'options' => [
                'delimiters' => [],
            ],
        ]);

        $stylesheet = $renderer->generateStylesheet();
        $this->assertStringNotContainsString('integrity=', $stylesheet);
    }
}