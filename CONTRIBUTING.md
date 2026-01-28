# Contributing to Laravel KaTeX

First off, thank you for considering contributing to Laravel KaTeX! It's people like you that make this package better.

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues as you might find out that you don't need to create one. When you are creating a bug report, please include as many details as possible:

* **Use a clear and descriptive title**
* **Describe the exact steps which reproduce the problem**
* **Provide specific examples to demonstrate the steps**
* **Describe the behavior you observed after following the steps**
* **Explain which behavior you expected to see instead and why**
* **Include screenshots if relevant**
* **Include your environment details** (PHP version, Laravel version, package version)

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, please include:

* **Use a clear and descriptive title**
* **Provide a step-by-step description of the suggested enhancement**
* **Provide specific examples to demonstrate the steps**
* **Describe the current behavior and explain which behavior you expected to see instead**
* **Explain why this enhancement would be useful**

### Pull Requests

* Fill in the required template
* Follow the PHP coding standards (PSR-12)
* Include thoughtfully-worded, well-structured tests
* Document new code with PHPDoc comments
* End all files with a newline

## Development Process

### Setup

1. Fork the repository
2. Clone your fork: `git clone https://github.com/your-username/laravel-katex.git`
3. Install dependencies: `composer install`
4. Create a branch: `git checkout -b feature/your-feature-name`

### Coding Standards

This project follows PSR-12 coding standards. We use Laravel Pint for automatic formatting:

```bash
composer format
```

### Testing

All new features must include tests. We aim for 100% code coverage:

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage
```

### Static Analysis

We use PHPStan at level 8 for static analysis:

```bash
composer analyse
```

### Before Submitting

1. Run the test suite: `composer test`
2. Run static analysis: `composer analyse`
3. Format your code: `composer format`
4. Update documentation if needed
5. Update CHANGELOG.md

### Commit Messages

* Use the present tense ("Add feature" not "Added feature")
* Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
* Limit the first line to 72 characters or less
* Reference issues and pull requests liberally after the first line

### Git Workflow

We use the [GitHub Flow](https://guides.github.com/introduction/flow/):

1. Create a branch from `main`
2. Make your changes
3. Push to your fork
4. Submit a Pull Request

## Style Guides

### PHP Style Guide

* Use strict types: `declare(strict_types=1);`
* Use type hints for all parameters and return types
* Use property type declarations (PHP 7.4+)
* Document all public methods with PHPDoc
* Keep methods focused and single-purpose
* Follow SOLID principles

### Documentation Style Guide

* Use Markdown for all documentation
* Include code examples
* Keep examples realistic and practical
* Update README.md for user-facing changes

## Project Structure

```
src/
├── Compilers/           # Blade directive compilers
├── Exceptions/          # Custom exceptions
├── Facades/            # Laravel facades
├── Services/           # Core business logic
├── Support/            # Helper classes
├── KatexServiceProvider.php
└── helpers.php

tests/
├── Feature/            # Integration tests
└── Unit/              # Unit tests

config/
└── katex.php          # Configuration file

resources/
└── views/             # Blade views
```

## Release Process

Maintainers will handle releases. The process includes:

1. Update version in composer.json
2. Update CHANGELOG.md
3. Create a git tag
4. Push to GitHub
5. Create GitHub release

## Questions?

Feel free to create an issue with the "question" label if you have any questions about contributing.

---

Thank you for contributing! 🎉