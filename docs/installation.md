---
title: Installation
weight: 2
nav_title: Installation
order: 20
---

# Installation

## Choose your install path

Layup is a Filament form field first; the bundled Pages resource is a turnkey example built on top of it. Pick the path that fits how you plan to use it.

| If you want to... | Choose | Start here |
| --- | --- | --- |
| Add a visual editor to an existing model and Filament resource | Field-only | [Field-only installation](field-only-installation.md), then [embed the field](embedding-the-field.md) |
| Manage standalone pages with nesting, publishing, SEO, and frontend routes | Bundled Pages CMS | Continue with this guide |

This guide covers the bundled Pages CMS path. It creates Layup's page tables and exposes the Pages resource in your Filament panel. The field-only guide is self-contained; do not run this guide's Pages migration steps for that path.

Either way, the field looks like this:

```php
use Crumbls\Layup\Forms\Components\LayupBuilder;

LayupBuilder::make('content')->columnSpanFull()
```

## Requirements

- PHP 8.3+
- Laravel 12 or 13
- Filament 5

`livewire/livewire` is suggested only when you build widgets that extend `BaseLivewireWidget`.

## Prerequisites

Layup requires a working Filament installation. If you have not set up Filament yet:

```bash
composer require filament/filament
php artisan filament:install --panels
```

See the [Filament installation docs](https://filamentphp.com/docs/panels/installation) for details.

## Install via Composer

```bash
composer require crumbls/layup
```

## Register the plugin

Add `LayupPlugin` to your Filament panel provider (e.g., `app/Providers/Filament/AdminPanelProvider.php`):

```php
use Crumbls\Layup\LayupPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            LayupPlugin::make(),
        ]);
}
```

Registering the plugin makes the `LayupBuilder` field available in any Filament form and (unless disabled in config) registers the Pages resource in your sidebar.

## Quick install (recommended)

Run the install command to handle everything in one step:

```bash
php artisan layup:install
```

This:

- Publishes the config file
- Runs database migrations (creates `layup_pages` and `layup_page_revisions`)
- Creates a storage symlink (`storage:link`) so uploaded images are web-accessible
- Creates the frontend layout component if it does not exist
- Publishes Filament assets (CSS)
- Generates the Tailwind safelist
- Checks your setup and warns about any issues

After running install, skip to [Tailwind safelist setup](#tailwind-safelist-setup).

## Manual installation

If you prefer to run each step yourself:

```bash
php artisan vendor:publish --tag=layup-config
php artisan migrate
php artisan storage:link
php artisan filament:assets
php artisan layup:safelist
```

This creates two tables: `layup_pages` (configurable name) and `layup_page_revisions`.

## Tailwind safelist setup

Layup dynamically generates CSS classes for column widths, spacing, and user-defined styles. Tailwind needs to know about these classes.

### Tailwind v4

Add to your CSS entry point:

```css
@source "../../storage/layup-safelist.txt";
```

### Tailwind v3

Add to your `tailwind.config.js`:

```js
module.exports = {
    content: [
        // ...
        './storage/layup-safelist.txt',
    ],
};
```

Generate the safelist file (if you did not use `layup:install`):

```bash
php artisan layup:safelist
```

When `safelist.auto_sync` is enabled in config (the default), the safelist regenerates automatically every time a page is saved.

### Build pipeline integration

Add the safelist command to your build script so it always runs before Tailwind compiles:

```json
{
    "scripts": {
        "build": "php artisan layup:safelist && vite build"
    }
}
```

### Rebuild frontend assets

```bash
npm run build
```

Without the safelist and rebuild steps, the page builder will work in the admin panel but frontend pages will have broken or missing styling.

## Publish assets (optional)

| Tag | Command | Description |
|-----|---------|-------------|
| `layup-config` | `php artisan vendor:publish --tag=layup-config` | Config file |
| `layup-views` | `php artisan vendor:publish --tag=layup-views` | Blade templates |
| `layup-routes` | `php artisan vendor:publish --tag=layup-routes` | Route file |
| `layup-scripts` | `php artisan vendor:publish --tag=layup-scripts` | Alpine.js components |
| `layup-templates` | `php artisan vendor:publish --tag=layup-templates` | Page templates |
| `layup-translations` | `php artisan vendor:publish --tag=layup-translations` | Language files |
| `layup-stubs` | `php artisan vendor:publish --tag=layup-stubs` | Widget scaffold stubs |

## Verify installation

Run the health check and rebuild your frontend assets after configuring the Tailwind safelist:

```bash
php artisan layup:doctor
npm run build
```

Then verify the complete path:

1. Open `/admin/pages` (or your panel path) and create a page. The visual builder should show rows, columns, and the widget picker.
2. Add a widget, save the page, and publish it.
3. Open the page's frontend URL. The widget should render with the styling from your rebuilt assets.

If `layup:doctor` reports a failure, resolve it before creating content. Warnings can be intentional; the command explains the expected fix or configuration choice.

## Troubleshooting

`php artisan layup:doctor` runs a battery of checks and prints `passed / warning / failure` counts. Below are the failures you are most likely to hit and how to fix each.

### "LayupPlugin is not registered in any Filament panel"

The `LayupPlugin::make()` call is missing from your panel provider. Add it under `->plugins([...])` (see [Register the plugin](#register-the-plugin)). After editing, clear the config cache: `php artisan config:clear`.

### "Table 'layup_pages' does not exist"

Migrations have not run. Run:

```bash
php artisan migrate
```

If you are intentionally on the field-only path and do not want these tables, see [Field-only installation](field-only-installation.md) -- the doctor warning is expected there.

### "Storage symlink missing"

Uploaded images will not be web-accessible. Run:

```bash
php artisan storage:link
```

### "Layout component [app] not found"

Layup expects a Blade component matching `frontend.layout` (default: `app`) to exist at `resources/views/components/app.blade.php`. Either:

- Run `php artisan layup:install` -- it copies a starter layout component if one is missing, or
- Create the component manually, or
- Set `frontend.layout` in `config/layup.php` to point to your existing layout component (e.g., `'layouts.app'` for `resources/views/components/layouts/app.blade.php`).

### "Layout does not include @layupScripts"

Interactive widgets (accordion, tabs, countdown, modals, slider, etc.) will not function without this directive. Add it to your frontend layout, typically just before the closing `</body>` tag:

```blade
@layupScripts
</body>
```

### "Safelist file does not exist" / Frontend pages render without styling

Tailwind cannot see Layup's dynamically-generated classes. Verify three things in order:

1. The safelist file exists -- run `php artisan layup:safelist` to generate it.
2. Your Tailwind config or CSS entry point references the safelist path (see [Tailwind safelist setup](#tailwind-safelist-setup)).
3. You ran `npm run build` (or your equivalent) **after** adding the safelist reference.

### "Upload disk 'public' is not configured"

Your `config/filesystems.php` does not have a disk matching `uploads.disk`. Either configure the disk or set `uploads.disk` in `config/layup.php` to a disk you do have configured.

If a check is not listed here, run `php artisan layup:doctor` and read the message inline -- each failure includes a one-line fix hint.

## Upgrades

See [CHANGELOG.md](https://github.com/Crumbls/layup/blob/main/CHANGELOG.md) for release notes, breaking changes, and version-by-version migration guidance.
