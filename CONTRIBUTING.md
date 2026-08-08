# Contributing to Layup

Thanks for your interest in contributing! Here's how to get started.

## Development Setup

```bash
# Clone the repo
git clone https://github.com/Crumbls/layup.git
cd layup

# Install dependencies
composer install

# Run tests
vendor/bin/pest

# Run code style fixer
vendor/bin/pint

# Run static analysis / refactoring
vendor/bin/rector --dry-run
```

## Code Style

Layup uses [Laravel Pint](https://laravel.com/docs/pint) with the Laravel preset. Run it before committing:

```bash
vendor/bin/pint
```

The pre-push hook runs Pint in `--test` mode and Pest automatically. If either fails, the push is blocked.

## Running Tests

```bash
# All tests
vendor/bin/pest

# Specific file
vendor/bin/pest tests/Unit/WidgetTest.php

# Filter by name
vendor/bin/pest --filter="renders heading widget"

# Parallel (faster)
vendor/bin/pest --parallel
```

## Writing Tests

Tests live in `tests/Unit/` and `tests/Feature/`. We use [Pest](https://pestphp.com/).

**Widget tests** should cover:
- Default data structure
- Form schema returns valid Filament components
- Preview renders something useful
- Frontend rendering produces correct HTML
- Edge cases (empty data, missing fields, long content)

**Feature tests** should cover:
- Artisan commands
- HTTP routes (frontend rendering)
- Full page builder lifecycle

Example widget test:

```php
it('renders with default data', function () {
    $widget = new MyWidget(['data' => MyWidget::getDefaultData()]);
    $html = $widget->render()->render();

    expect($html)->toBeString()->not->toBeEmpty();
});

it('has valid form schema', function () {
    $schema = MyWidget::getContentFormSchema();

    expect($schema)->toBeArray()->not->toBeEmpty();
    expect($schema[0])->toBeInstanceOf(\Filament\Forms\Components\Component::class);
});
```

## Building Custom Widgets

See the [Custom Widgets](README.md#custom-widgets) section in the README. The short version:

1. Extend `Crumbls\Layup\View\BaseWidget`
2. Implement `getType()`, `getLabel()`, `getContentFormSchema()`, `getDefaultData()`, `render()`
3. Drop it in `App\Layup\Widgets` (auto-discovered) or register via config/plugin

If you're building a widget package for others to use, publish it as a Composer package with a service provider that registers the widgets.

## Pull Requests

1. Fork the repo and create a feature branch
2. Write tests for your changes
3. Run `vendor/bin/pint` and `vendor/bin/pest`
4. Open a PR with a clear description of what and why

Keep PRs focused — one feature or fix per PR. Large refactors should be discussed in an issue first.

## Releasing documentation

Package documentation is versioned with the package. Documentation changes in this repository become public after a release; pushing a documentation-only branch does not update the documentation site.

Before merging a documentation change, run:

```bash
bin/docs-lint
```

When release-please publishes a Layup release, the release workflow sends the package name and tag to the documentation site's webhook. The site then fetches the Markdown from that release's GitHub reference and serves it with the matching documentation version.

Maintainers must configure these Layup repository secrets for the notification step:

- `DOCS_SYNC_WEBHOOK_URL` -- the documentation site's `/webhooks/docs` endpoint
- `DOCS_SYNC_WEBHOOK_SECRET` -- the shared secret matching the site's `DOCS_WEBHOOK_SECRET` setting

The workflow safely skips notification when either secret is missing. For an unpublished local preview, run the documentation site's local sync command with this package as its source path; see the `crumbls-docs` repository README.

## Reporting Issues

Open an issue with:
- What you expected to happen
- What actually happened
- Steps to reproduce (minimal example)
- PHP, Laravel, and Filament versions
