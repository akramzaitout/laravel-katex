<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex;

use AkramZaitout\LaravelKatex\Compilers\KatexBladeCompiler;
use AkramZaitout\LaravelKatex\Services\KatexRenderer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * KaTeX Service Provider for Laravel
 *
 * Registers KaTeX services and Blade directives for rendering mathematical
 * equations using the KaTeX library.
 *
 * @author Akram Zaitout
 *
 * @link https://katex.org/
 */
class KatexServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishConfiguration();
        $this->publishViews();
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'katex');
        $this->registerBladeDirectives();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfiguration();
        $this->registerServices();
    }

    /**
     * Publish configuration file.
     */
    protected function publishConfiguration(): void
    {
        $this->publishes([
            __DIR__ . '/../config/katex.php' => config_path('katex.php'),
        ], 'katex-config');
    }

    /**
     * Publish view files.
     */
    protected function publishViews(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/katex'),
        ], 'katex-views');
    }

    /**
     * Merge package configuration with application configuration.
     */
    protected function mergeConfiguration(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/katex.php',
            'katex'
        );
    }

    /**
     * Register package services in the container.
     */
    protected function registerServices(): void
    {
        // Register the renderer as a singleton
        $this->app->singleton(KatexRenderer::class, function (Application $app) {
            return new KatexRenderer(
                $app['config']->get('katex')
            );
        });

        // Register the Blade compiler as a singleton
        $this->app->singleton(KatexBladeCompiler::class, function (Application $app) {
            return new KatexBladeCompiler(
                $app->make(KatexRenderer::class)
            );
        });

        // Register facade alias
        $this->app->alias(KatexRenderer::class, 'katex');
    }

    /**
     * Register all Blade directives for KaTeX.
     */
    protected function registerBladeDirectives(): void
    {
        $compiler = $this->app->make(KatexBladeCompiler::class);

        // @katexStyles - Renders KaTeX CSS stylesheet link
        Blade::directive('katexStyles', function () use ($compiler) {
            return $compiler->compileStyles();
        });

        // @katexScripts - Renders KaTeX JavaScript with auto-render extension
        Blade::directive('katexScripts', function ($expression) use ($compiler) {
            return $compiler->compileScripts($expression);
        });

        // @katex - Renders inline math expression
        Blade::directive('katex', function ($expression) use ($compiler) {
            return $compiler->compileInlineMath($expression);
        });

        // @katexBlock - Renders display mode (block) math expression
        Blade::directive('katexBlock', function ($expression) use ($compiler) {
            return $compiler->compileBlockMath($expression);
        });

        // @katexComponent - Renders using a Blade component
        Blade::directive('katexComponent', function ($expression) use ($compiler) {
            return $compiler->compileComponent($expression);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            KatexRenderer::class,
            KatexBladeCompiler::class,
            'katex',
        ];
    }
}
