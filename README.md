# Laravel KaTeX

<p align="center">
  <img src="./assets/logo.png" alt="Laravel KaTeX Package Logo" width="200" />
</p>

A comprehensive, production-ready Laravel package for rendering beautiful mathematical expressions using [KaTeX](https://katex.org/). Built with modern PHP 8.1+ features, dependency injection, and following Laravel best practices.

## ✨ Features

- 🎯 **Clean Architecture** - Separation of concerns with dedicated services, compilers, and validators
- 🔒 **Security First** - XSS protection, SRI hashes, and input validation
- 🚀 **Performance** - Efficient rendering with optional caching support
- 📝 **Multiple APIs** - Blade directives, Facade, helper functions, and Blade components
- ⚙️ **Highly Configurable** - Extensive configuration options with sensible defaults
- 🧪 **Well Tested** - Comprehensive unit and feature tests
- 📚 **Fully Documented** - Detailed PHPDoc comments and documentation
- 🎨 **Type Safe** - Full PHP 8.1+ type hints and strict types
- 🔄 **Auto-Discovery** - Automatic package discovery for Laravel 10+

## 📋 Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x
- ext-json

## 📦 Installation

Install the package via Composer:

```bash
composer require akram-zaitout/laravel-katex
```

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=katex-config
```

This creates `config/katex.php` where you can customize all aspects of the package.

### Publish Views (Optional)

```bash
php artisan vendor:publish --tag=katex-views
```

## 🚀 Quick Start

### Basic Setup

In your Blade layout file (e.g., `resources/views/layouts/app.blade.php`):

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Application</title>
    
    @katexStyles
</head>
<body>
    @yield('content')
    
    @katexScripts
</body>
</html>
```

### Using Blade Directives

```blade
{{-- Inline math --}}
<p>The quadratic formula is @katex('x = \frac{-b \pm \sqrt{b^2-4ac}}{2a}')</p>

{{-- Display (block) math --}}
@katexBlock('\int_{-\infty}^{\infty} e^{-x^2} dx = \sqrt{\pi}')

{{-- With variables --}}
@php
    $equation = 'E = mc^2';
@endphp
<p>Einstein's equation: @katex($equation)</p>
```

## 📖 Usage

### 1. Blade Directives

#### @katexStyles

Renders the KaTeX CSS stylesheet:

```blade
@katexStyles
```

#### @katexScripts

Renders KaTeX JavaScript with optional configuration:

```blade
{{-- Default options --}}
@katexScripts

{{-- Custom options --}}
@katexScripts([
    'delimiters' => [
        ['left' => '$', 'right' => '$', 'display' => false],
        ['left' => '$$', 'right' => '$$', 'display' => true],
    ],
    'throwOnError' => false,
    'macros' => [
        '\\RR' => '\\mathbb{R}',
    ]
])
```

#### @katex

Renders inline mathematical expressions:

```blade
@katex('a^2 + b^2 = c^2')
```

#### @katexBlock

Renders display mode (block) mathematical expressions:

```blade
@katexBlock('\sum_{i=1}^{n} i = \frac{n(n+1)}{2}')
```

### 2. Facade

Use the `Katex` facade for programmatic access:

```php
use AkramZaitout\LaravelKatex\Facades\Katex;

// Generate stylesheet
$styles = Katex::generateStylesheet();

// Generate scripts
$scripts = Katex::generateScripts(['throwOnError' => false]);

// Wrap expressions
$inline = Katex::wrapInline('x^2');
$display = Katex::wrapDisplay('\int_0^\infty');

// Get configuration
$version = Katex::getConfig('version');
```

### 3. Helper Functions

```php
// Get renderer instance
$renderer = katex();

// Render inline math
$inline = katex_inline('a^2 + b^2 = c^2');

// Render display math
$display = katex_display('\sum_{i=1}^{n} i');

// Generate assets
$styles = katex_styles();
$scripts = katex_scripts(['throwOnError' => false]);
```

### 4. Blade Component

```blade
<x-katex::math 
    expression="x^2 + y^2 = r^2" 
    display="false"
    class="my-math"
    id="pythagorean"
/>
```

### 5. Service Injection

```php
use AkramZaitout\LaravelKatex\Services\KatexRenderer;

class MathController extends Controller
{
    public function __construct(
        protected KatexRenderer $katex
    ) {}
    
    public function show()
    {
        $expression = $this->katex->wrapInline('E=mc^2');
        
        return view('math.show', compact('expression'));
    }
}
```

## ⚙️ Configuration

### Environment Variables

Set these in your `.env` file:

```env
KATEX_VERSION=0.16.28
KATEX_CDN=https://cdn.jsdelivr.net/npm/katex
KATEX_CSS_INTEGRITY=sha384-Wsr4Nh3yrvMf2KCebJchRJoVo1gTU6kcP05uRSh5NV3sj9+a8IomuJoQzf3sMq4T
KATEX_JS_INTEGRITY=sha384-+W9OcrYK2/bD7BmUAk+xeFAyKp0QjyRQUCxeU31dfyTt/FrPsUgaBTLLkVf33qWt
KATEX_AUTO_RENDER_INTEGRITY=sha384-hCXGrW6PitJEwbkoStFjeJxv+fSOOQKOPbJxSfM6G5sWZjAyWhXiTIIAmQqnlLlh
```

### Configuration File

Publish and edit `config/katex.php`:

```php
return [
    'version' => '0.16.28',
    'cdn' => 'https://cdn.jsdelivr.net/npm/katex',
    
    'options' => [
        'delimiters' => [
            ['left' => '$$', 'right' => '$$', 'display' => true],
            ['left' => '\\(', 'right' => '\\)', 'display' => false],
        ],
        'throwOnError' => false,
        'errorColor' => '#cc0000',
        'macros' => [
            '\\RR' => '\\mathbb{R}',
            '\\NN' => '\\mathbb{N}',
        ],
    ],
];
```

## 🎓 Examples

### Educational Content

```blade
<article class="lesson">
    <h1>Calculus Fundamentals</h1>
    
    <p>The derivative of @katex('x^n') is @katex('nx^{n-1}').</p>
    
    <h2>Example</h2>
    @katexBlock('\frac{d}{dx}(x^3) = 3x^2')
    
    <h2>The Power Rule</h2>
    @katexBlock('\frac{d}{dx}(x^n) = nx^{n-1}')
</article>
```

### Scientific Documentation

```blade
<div class="physics-formulas">
    <h3>Schrödinger Equation</h3>
    @katexBlock('i\hbar\frac{\partial}{\partial t}\Psi = \hat{H}\Psi')
    
    <h3>Maxwell's Equations</h3>
    @katexBlock('\begin{aligned}
        \nabla \cdot \mathbf{E} &= \frac{\rho}{\epsilon_0} \\
        \nabla \cdot \mathbf{B} &= 0 \\
        \nabla \times \mathbf{E} &= -\frac{\partial \mathbf{B}}{\partial t} \\
        \nabla \times \mathbf{B} &= \mu_0\mathbf{J} + \mu_0\epsilon_0\frac{\partial \mathbf{E}}{\partial t}
    \end{aligned}')
</div>
```

### Dynamic Content

```blade
@php
    $equations = [
        'Pythagorean Theorem' => 'a^2 + b^2 = c^2',
        'Euler\'s Identity' => 'e^{i\pi} + 1 = 0',
        'Einstein\'s Mass-Energy' => 'E = mc^2',
    ];
@endphp

<ul class="famous-equations">
    @foreach($equations as $name => $equation)
        <li>
            <strong>{{ $name }}:</strong>
            @katex($equation)
        </li>
    @endforeach
</ul>
```

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
composer test

# Run with coverage
composer test-coverage

# Run static analysis
composer analyse

# Format code
composer format
```

## 🔒 Security

### XSS Protection

All user input is automatically escaped using Laravel's `e()` helper:

```blade
{{-- Safe: HTML is escaped --}}
@katex($userInput)
```

### Subresource Integrity

SRI hashes ensure CDN files haven't been tampered with:

```php
'css_integrity' => 'sha384-Wsr4Nh3yrvMf2KCebJchRJoVo1gTU6kcP05uRSh5NV3sj9+a8IomuJoQzf3sMq4T',
```

### Trust Settings

By default, `\url` and `\href` commands are disabled. Enable only if needed:

```php
'options' => [
    'trust' => false, // Keep disabled for user-generated content
],
```

## 📊 Architecture

```
src/
├── Compilers/
│   └── KatexBladeCompiler.php    # Blade directive compilation
├── Exceptions/
│   └── InvalidKatexConfigurationException.php
├── Facades/
│   └── Katex.php                  # Facade for easy access
├── Services/
│   └── KatexRenderer.php          # Core rendering logic
├── Support/
│   └── ConfigValidator.php        # Configuration validation
├── KatexServiceProvider.php       # Service provider
└── helpers.php                     # Helper functions
```

## 🔄 Updating KaTeX

To update to a new KaTeX version:

1. Update version in `config/katex.php` or `.env`
2. Get new SRI hashes from [jsDelivr](https://www.jsdelivr.com/package/npm/katex)
3. Update integrity values in config

```php
'version' => '0.16.28',
'css_integrity' => 'sha384-new-hash',
'js_integrity' => 'sha384-new-hash',
'auto_render_integrity' => 'sha384-new-hash',
```

## 🐛 Troubleshooting

### Math Not Rendering

1. Check browser console for errors
2. Ensure `@katexStyles` is in `<head>`
3. Ensure `@katexScripts` is before `</body>`
4. Verify delimiters match your content

### Single Dollar Signs Not Working

Enable in configuration:

```php
'options' => [
    'delimiters' => [
        ['left' => '$', 'right' => '$', 'display' => false],
    ],
],
```

### Styling Issues

Ensure KaTeX CSS loads before your custom CSS.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Run tests (`composer test`)
4. Run static analysis (`composer analyse`)
5. Format code (`composer format`)
6. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
7. Push to the branch (`git push origin feature/AmazingFeature`)
8. Open a Pull Request

## 📝 License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## 🙏 Credits

- [KaTeX](https://katex.org/) - The math typesetting library
- [Laravel](https://laravel.com/) - The PHP framework
- [Akram Zaitout](https://github.com/akram-zaitout) - Package author

## 📚 Resources

- [KaTeX Documentation](https://katex.org/docs/api.html)
- [KaTeX Supported Functions](https://katex.org/docs/supported.html)
- [LaTeX Mathematics Guide](https://en.wikibooks.org/wiki/LaTeX/Mathematics)

---

<p align="center">Made with ❤️ by <a href="https://github.com/akram-zaitout">Akram Zaitout</a></p>