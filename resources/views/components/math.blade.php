@props([
    'expression' => '',
    'display' => false,
    'class' => '',
    'id' => '',
])

@php
    $renderer = app(\AkramZaitout\LaravelKatex\Services\KatexRenderer::class);
    $wrapped = $display ? $renderer->wrapDisplay($expression) : $renderer->wrapInline($expression);
@endphp

<span @if ($id) id="{{ $id }}" @endif class="katex-expression {{ $class }}"
    data-display="{{ $display ? 'true' : 'false' }}">
    {!! $wrapped !!}
</span>
