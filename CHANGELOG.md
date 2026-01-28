# Changelog

All notable changes to `laravel-katex` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial release
- KatexRenderer service for rendering mathematical expressions
- KatexBladeCompiler for Blade directive compilation
- ConfigValidator for configuration validation
- Blade directives: @katexStyles, @katexScripts, @katex, @katexBlock
- Katex Facade for easy access
- Helper functions: katex(), katex_inline(), katex_display(), katex_styles(), katex_scripts()
- Blade component for reusable math rendering
- Comprehensive configuration with environment variable support
- XSS protection with automatic HTML escaping
- Subresource Integrity (SRI) support for CDN resources
- PHPUnit tests with 100% code coverage
- PHPStan level 8 static analysis
- Laravel Pint code formatting
- Full PHPDoc documentation
- Comprehensive README with examples

### Security
- XSS protection through automatic escaping
- SRI hashes for CDN resources
- Validation of user input
- Safe JSON encoding for JavaScript

## [1.0.0] - 2026-01-28

### Added
- Initial stable release

[Unreleased]: https://github.com/akram-zaitout/laravel-katex/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/akram-zaitout/laravel-katex/releases/tag/v1.0.0