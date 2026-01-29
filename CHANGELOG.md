# Changelog

## [1.0.4] - 2026-01-29

### 🔨 Chores

- Add .gitattributes for consistent line endings and export ignores (11ac8dee1a4bc5827127d05726c7ab1bc35e9749)

### 📦 Other Changes

- Akramzaitout ()

## [1.0.3] - 2026-01-29

### 🔨 Chores

- Bump package version to 1.0.2 (e0dfb436f43a8b55d26f665266c3fa77537ab1d9)

## [0.0.1] - 2026-01-29

### 👷 CI/CD

- Add PHP 8.1 to Laravel 12 test matrix (5f14a172e25c9111ad82fe398802902487f99146)
- Update GitHub workflows to trigger tests on main and release after tests pass (e7bc2fb0f100c74988ef824f171dd5dbea998bc9)
- Replace release workflow with comprehensive test matrix (fe89f2539451cc83a334bc607a31e49c9f26cf3f)
- Add automated release workflow with semantic versioning (7bfbc5e39d7afeeb554468e9afc5e3b98efb7e5f)

### 📦 Other Changes

-  (- Update dev dependencies to support newer versions of orchestra/testbench and phpunit/phpunit)
-  (- Remove outdated examples and documentation from README.md to keep it concise)
- Akramzaitout ()
- Merge pull request #2 from akramzaitout/chore/setup-automated-releases (9ce84d35e158084310edb0b72e1e56f3d0191434)
-  (The workflow runs tests, executes release.php to bump version based on conventional commits,)
-  (generates changelog, creates Git tag, and publishes GitHub release.)
-  (Also adds release.php script for semantic versioning and updates README badges.)
- Akramzaitout ()

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