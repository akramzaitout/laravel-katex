<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex\Services;

use AkramZaitout\LaravelKatex\Exceptions\LivewireNotInstalledException;

class LivewireIntegrator
{
    /**
     * Generate the Livewire integration script.
     *
     * @param string $jsonOptions JSON-encoded KaTeX options
     * @throws LivewireNotInstalledException If Livewire is not installed
     */
    public function generateScript(string $jsonOptions): string
    {
        if (! $this->isLivewireInstalled()) {
            throw new LivewireNotInstalledException('Livewire is not installed.');
        }

        return <<<JS
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (typeof Livewire === 'undefined') {
        console.warn('KaTeX Livewire integration is enabled but Livewire is not detected.');
        return;
    }

    const reRenderMath = (el) => {
         if (!el) return;
         renderMathInElement(el, {$jsonOptions});
    };

    if (Livewire.hook) {
         Livewire.hook('morph.updated', ({ el, component }) => {
             reRenderMath(el);
         });
         Livewire.hook('element.initialized', ({ el, component }) => {
             reRenderMath(el);
         });
    } else if (window.livewire && window.livewire.hook) {
         window.livewire.hook('message.processed', (message, component) => {
             reRenderMath(component.el);
         });
    }
});
</script>
JS;
    }

    /**
     * Check if Livewire is installed.
     *
     * @return bool
     */
    protected function isLivewireInstalled(): bool
    {
        return class_exists('Livewire\Livewire') || class_exists('Livewire\Component');
    }
}
