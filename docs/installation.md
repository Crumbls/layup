---
title: Installation
weight: 2
---

## Requirements

- PHP 8.3+
- Laravel 12+
- Filament 5
- Livewire 4

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

Without this step, the Pages resource will not appear in your Filament sidebar.

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

Run the health check command to confirm everything is set up correctly:

```bash
php artisan layup:doctor
```

Then visit `/admin/pages` (or your panel path) and create a new page. You should see the visual builder with rows, columns, and the widget picker.
