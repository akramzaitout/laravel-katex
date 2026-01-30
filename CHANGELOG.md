# Changelog

## [1.2.2] - 2026-01-30

### 👷 CI/CD

- Restructure test matrix to include only valid PHP/Laravel combos (54db55807983dcdbac200e6fb7d234b5c018bed7)
- Restructure workflow to test all PHP/Laravel combos (289dc1b5b77a6383340f2a44a9fef64e34d9fe23)

### 📦 Other Changes

- Akramzaitout ()
- Akramzaitout ()

## [1.2.1] - 2026-01-30

### 👷 CI/CD

- Simplify workflow matrix to supported PHP/Laravel pairs (1b05b187e03f87b33b0fa3982ef0a8992a9949c8)

### 📦 Other Changes

- Akramzaitout ()

## [1.2.0] - 2026-01-30

### ✨ Features

- Add version option to download command (f582ad45ba1a02d4d5ebc9241c1167399ccca163)

### 🐛 Bug Fixes

- Add `static $latestResponse` property to `KatexDownloadCommandTest` to resolve Testbench compatibility issues. (5879c15b3e08775c220b198d73a0f516eead56f1)

### 📦 Other Changes

- Merge pull request #4 from akramzaitout/feat/download-command-version (0ff4bc0277a195c06c4f3b63c518e75c3beb2a7e)
- Akramzaitout ()

## [1.1.0] - 2026-01-30

### ✨ Features

- Add support for local KaTeX assets (6ed8d7f1142bee99ff5ec5cf3b7ebdbb0ef61b71)
- Add option to load KaTeX assets locally (9bf40feaf8bd09d77b53417e8c70991b83cbf6c3)
- Add console command for downloading KaTeX assets (39fc00c3e9ea0ccc23a6dfc8c56f9d4833fe6fa3)
- Add console command to download KaTeX assets for offline use (1e681355f190bcfec5240fc8bcba8e78e2077733)

### ♻️  Refactoring

- Replace Orchestra Testbench TestCase with PHPUnit's and add a mock for the asset helper function. (afb7459e6c6434982fa824205580ad7362d704a7)

### 📚 Documentation

- Add local asset management section to README (d19a23d33df8cd2761a7fba53aa056ca34dfb2a9)

### ✅ Tests

- Switch to Orchestra Testbench for Laravel testing (9734fdae9ee58e179dba2d9fc5c35c008e3fac9e)
- Add test for local assets generation in KatexRenderer (cada2cb55dfe7c46fc38fbb8803a1c81b5bb9c97)

### 👷 CI/CD

- Remove branch filtering for push and pull request triggers in tests workflow. (fa41ae0df2b6b4b36de3a6af481894c3a8e45f4d)

### 📦 Other Changes

- Merge pull request #3 from akramzaitout/feat/local-assets-support (c1aaf1b2f7e89510310e4c252567101dfe5b2aa6)
- Akramzaitout ()
-  (Include instructions for downloading assets and enabling local mode.)
- Akramzaitout ()
-  (local asset links when the 'use_local_assets' configuration option is)
-  (enabled. The test ensures CSS and JS links point to local vendor paths)
-  (and do not contain CDN URLs or integrity attributes.)
- Akramzaitout ()
-  (The changes modify the `getCssTag` and `getScripts` methods to conditionally use the `asset()` helper for local paths when the configuration is enabled, while preserving the CDN fallback for backward compatibility.)
- Akramzaitout ()
- Akramzaitout ()
- Akramzaitout ()
- Akramzaitout ()

## [1.0.7] - 2026-01-30

### 📚 Documentation

- Remove contributing guidelines and refine README features. (5832f99e1b24e722750a42e052127e5f820b9bdc)

## [1.0.6] - 2026-01-29

### 📚 Documentation

- Add Buy Me A Coffee link to README (6152f9a3d241ace973f5452c988d27526e2342d8)

### 📦 Other Changes

- Akramzaitout ()

## [1.0.5] - 2026-01-29

### 📚 Documentation

- Improve README formatting and badge readability (67242345b01cbd84dcf039c37a776d2928d9d033)

### 📦 Other Changes

- Akramzaitout ()

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