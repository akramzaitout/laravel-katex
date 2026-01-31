<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | KaTeX Version
    |--------------------------------------------------------------------------
    |
    | The version of KaTeX to load from the CDN. Update this when you want
    | to use a newer version of KaTeX.
    |
    | Current latest stable: 0.16.28
    | Check for updates: https://github.com/KaTeX/KaTeX/releases
    |
    */
    'version' => env('KATEX_VERSION', '0.16.28'),

    /*
    |--------------------------------------------------------------------------
    | Use Local Assets
    |--------------------------------------------------------------------------
    |
    | When set to true, KaTeX assets (CSS, JS, fonts) will be loaded from
    | the local public/vendor/katex directory instead of the CDN.
    | Run 'php artisan katex:download' to download the assets.
    |
    */
    'use_local_assets' => env('KATEX_USE_LOCAL_ASSETS', false),

    /*
    |--------------------------------------------------------------------------
    | CDN Base URL
    |--------------------------------------------------------------------------
    |
    | The base CDN URL for loading KaTeX assets. You can change this if you
    | want to use a different CDN or self-host KaTeX files.
    |
    | Supported CDNs:
    | - jsDelivr: https://cdn.jsdelivr.net/npm/katex
    | - unpkg: https://unpkg.com/katex
    | - cdnjs: https://cdnjs.cloudflare.com/ajax/libs/KaTeX
    |
    */
    'cdn' => env('KATEX_CDN', 'https://cdn.jsdelivr.net/npm/katex'),

    /*
    |--------------------------------------------------------------------------
    | Subresource Integrity (SRI) Hashes
    |--------------------------------------------------------------------------
    |
    | These integrity hashes ensure that the loaded files haven't been tampered
    | with. Update these when changing the KaTeX version.
    |
    | For version 0.16.28 (jsDelivr):
    | Get hashes from: https://www.jsdelivr.com/package/npm/katex
    |
    */
    'css_integrity' => env(
        'KATEX_CSS_INTEGRITY',
        'sha384-Wsr4Nh3yrvMf2KCebJchRJoVo1gTU6kcP05uRSh5NV3sj9+a8IomuJoQzf3sMq4T'
    ),

    'js_integrity' => env(
        'KATEX_JS_INTEGRITY',
        'sha384-+W9OcrYK2/bD7BmUAk+xeFAyKp0QjyRQUCxeU31dfyTt/FrPsUgaBTLLkVf33qWt'
    ),

    'auto_render_integrity' => env(
        'KATEX_AUTO_RENDER_INTEGRITY',
        'sha384-hCXGrW6PitJEwbkoStFjeJxv+fSOOQKOPbJxSfM6G5sWZjAyWhXiTIIAmQqnlLlh'
    ),

    /*
    |--------------------------------------------------------------------------
    | Math Delimiters
    |--------------------------------------------------------------------------
    |
    | Custom delimiters for inline and display math modes.
    | These are used by the @katex and @katexBlock directives.
    |
    */
    'delimiters' => [
        'inline' => [
            'left' => '\\(',
            'right' => '\\)',
        ],
        'display' => [
            'left' => '$$',
            'right' => '$$',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-Render Options
    |--------------------------------------------------------------------------
    |
    | Configuration for the KaTeX auto-render extension.
    | These options are merged with any options passed to @katexScripts.
    |
    | Documentation: https://katex.org/docs/autorender.html
    |
    */
    'options' => [
        /*
        |----------------------------------------------------------------------
        | Delimiters for Auto-Rendering
        |----------------------------------------------------------------------
        |
        | Define which delimiters trigger automatic math rendering.
        | Each delimiter has a 'left' and 'right' string, and a 'display' boolean.
        |
        */
        'delimiters' => [
            [
                'left' => '$$',
                'right' => '$$',
                'display' => true,
            ],
            [
                'left' => '\\[',
                'right' => '\\]',
                'display' => true,
            ],
            [
                'left' => '\\(',
                'right' => '\\)',
                'display' => false,
            ],
            // Uncomment to enable single dollar signs (use with caution)
            // [
            //     'left' => '$',
            //     'right' => '$',
            //     'display' => false,
            // ],
        ],

        /*
        |----------------------------------------------------------------------
        | Ignored HTML Tags
        |----------------------------------------------------------------------
        |
        | HTML tags that should be ignored during auto-rendering.
        |
        */
        'ignoredTags' => [
            'script',
            'noscript',
            'style',
            'textarea',
            'pre',
            'code',
            'option',
            'annotation',
            'annotation-xml',
        ],

        /*
        |----------------------------------------------------------------------
        | Ignored CSS Classes
        |----------------------------------------------------------------------
        |
        | Elements with these CSS classes will be skipped during auto-rendering.
        |
        */
        'ignoredClasses' => [
            'no-katex',
            'katex-ignore',
        ],

        /*
        |----------------------------------------------------------------------
        | Error Handling
        |----------------------------------------------------------------------
        |
        | throwOnError: If true, render errors throw exceptions.
        | errorColor: Color used for rendering error messages.
        |
        */
        'throwOnError' => false,
        'errorColor' => '#cc0000',

        /*
        |----------------------------------------------------------------------
        | Output Format
        |----------------------------------------------------------------------
        |
        | output: 'html' or 'mathml' or 'htmlAndMathml'
        | Determines the output format of rendered math.
        |
        */
        'output' => 'htmlAndMathml',

        /*
        |----------------------------------------------------------------------
        | Display Mode
        |----------------------------------------------------------------------
        |
        | displayMode: Force all math to be in display mode (centered, larger).
        | leqno: Place equation numbers on the left side.
        | fleqn: Render display math flush left.
        |
        */
        // 'displayMode' => false,
        // 'leqno' => false,
        // 'fleqn' => false,

        /*
        |----------------------------------------------------------------------
        | Trust Settings
        |----------------------------------------------------------------------
        |
        | trust: Allow \url and \href commands (security risk with user input).
        | strict: Warn or error on deprecated or non-standard commands.
        |
        */
        'trust' => false,
        'strict' => 'warn',

        /*
        |----------------------------------------------------------------------
        | Custom Macros
        |----------------------------------------------------------------------
        |
        | Define custom LaTeX macros for frequently used commands.
        | Example: '\\RR' => '\\mathbb{R}'
        |
        */
        'macros' => [
            // Add your custom macros here
            // '\\RR' => '\\mathbb{R}',
            // '\\NN' => '\\mathbb{N}',
            // '\\ZZ' => '\\mathbb{Z}',
            // '\\QQ' => '\\mathbb{Q}',
            // '\\CC' => '\\mathbb{C}',
        ],

        /*
        |----------------------------------------------------------------------
        | Additional Options
        |----------------------------------------------------------------------
        |
        | maxSize: Maximum allowed size for user-specified sizes.
        | maxExpand: Maximum number of macro expansions.
        | minRuleThickness: Minimum thickness of fraction lines.
        |
        */
        // 'maxSize' => 500,
        // 'maxExpand' => 1000,
        // 'minRuleThickness' => 0.04,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Integration
    |--------------------------------------------------------------------------
    |
    | Enable specific support for Laravel Livewire.
    | When enabled, a script will be injected to listen for Livewire events
    | and re-render KaTeX expressions when the DOM updates.
    |
    */
    'livewire' => env('KATEX_LIVEWIRE', false),

    /*
    |--------------------------------------------------------------------------
    | Performance Options
    |--------------------------------------------------------------------------
    |
    | preprocess: Whether to preprocess math before rendering.
    | Useful for large documents with many expressions.
    |
    */
    'preprocess' => env('KATEX_PREPROCESS', false),

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Enable caching of rendered expressions for better performance.
    |
    */
    'cache' => [
        'enabled' => env('KATEX_CACHE_ENABLED', false),
        'ttl' => env('KATEX_CACHE_TTL', 3600), // Cache time in seconds
        'driver' => env('KATEX_CACHE_DRIVER', 'file'), // Laravel cache driver
    ],
];
