<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex\Tests\Unit;

use AkramZaitout\LaravelKatex\Services\KatexRenderer;
use AkramZaitout\LaravelKatex\Services\LivewireIntegrator;
use PHPUnit\Framework\TestCase;

class LivewireIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_throws_exception_when_livewire_is_missing(): void
    {
        if (class_exists('Livewire\Livewire') || class_exists('Livewire\Component')) {
            $this->markTestSkipped('Livewire is installed, cannot test missing exception.');
        }

        $integrator = $this->getMockBuilder(LivewireIntegrator::class)
            ->onlyMethods(['isLivewireInstalled'])
            ->getMock();

        $integrator->method('isLivewireInstalled')->willReturn(false);

        $this->expectException(\AkramZaitout\LaravelKatex\Exceptions\LivewireNotInstalledException::class);
        $this->expectExceptionMessage('Livewire is not installed.');
        $integrator->generateScript('{}');
    }

    public function test_it_generates_script_when_livewire_is_present(): void
    {
        $integrator = $this->getMockBuilder(LivewireIntegrator::class)
            ->onlyMethods(['isLivewireInstalled'])
            ->getMock();
        
        $integrator->method('isLivewireInstalled')->willReturn(true);

        $script = $integrator->generateScript('{"foo":"bar"}');

        $this->assertStringContainsString('Livewire.hook', $script);
        $this->assertStringContainsString('{"foo":"bar"}', $script);
        $this->assertStringContainsString('document.addEventListener("DOMContentLoaded"', $script);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_renderer_calls_integrator_when_config_enabled(): void
    {
        // Simulate Livewire existence for the mock integrator or the real one if we used real
        // But here we can mock LivewireIntegrator
        
        $mockIntegrator = $this->createMock(LivewireIntegrator::class);
        $mockIntegrator->expects($this->once())
            ->method('generateScript')
            ->with('[]')
            ->willReturn('<script>console.log("mock livewire")</script>');

        $renderer = new KatexRenderer([
            'version' => '0.16.28',
            'cdn' => 'https://cdn',
            'options' => [],
            'livewire' => true,
        ], $mockIntegrator);

        $scripts = $renderer->generateScripts();
        $this->assertStringContainsString('<script>console.log("mock livewire")</script>', $scripts);
    }

    public function test_it_does_not_throw_exception_when_config_is_false_even_if_livewire_is_missing(): void
    {
        $mockIntegrator = $this->getMockBuilder(LivewireIntegrator::class)
            ->onlyMethods(['isLivewireInstalled'])
            ->getMock();

        // Simulate missing livewire
        $mockIntegrator->method('isLivewireInstalled')->willReturn(false);

        $renderer = new KatexRenderer([
            'version' => '0.16.28',
            'cdn' => 'https://cdn',
            'options' => [],
            'livewire' => false, // Disabled
        ], $mockIntegrator);

        // Should NOT throw exception
        $scripts = $renderer->generateScripts();
        
        $this->assertStringNotContainsString('Livewire', $scripts);
    }

    public function test_renderer_does_not_call_integrator_when_config_disabled(): void
    {
        $mockIntegrator = $this->createMock(LivewireIntegrator::class);
        $mockIntegrator->expects($this->never())
            ->method('generateScript');

        $renderer = new KatexRenderer([
            'version' => '0.16.28',
            'cdn' => 'https://cdn',
            'options' => [],
            'livewire' => false,
        ], $mockIntegrator);

        $renderer->generateScripts();
    }
}
