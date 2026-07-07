---
title: Field-only Installation
weight: 2
nav_title: Field-only Installation
order: 20
---

This is the minimal install path. Use it when you want `LayupBuilder` as a field on your own models and you do **not** want the bundled Pages resource in your Filament sidebar.

If you want the full Pages CMS (nested pages, scheduled publishing, frontend routes, SEO meta), follow the standard [Installation](installation.md) instead.

## Requirements

Same as the full install:

- PHP 8.3+
- Laravel 12 or 13
- Filament 5
- A working Filament panel

`livewire/livewire` is suggested only when you build widgets that extend `BaseLivewireWidget`.

## Step 1: Install via Composer

```bash
composer require crumbls/layup
```

## Step 2: Register the plugin

Add `LayupPlugin` to your Filament panel provider:

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

The plugin registers the `LayupBuilder` field, the widget registry, and the theme system regardless of whether the Pages resource is enabled.

## Step 3: Publish the config and disable the Pages resource

```bash
php artisan vendor:publish --tag=layup-config
```

Open `config/layup.php` and set `pages.enabled` to `false`:

```php
// config/layup.php
'pages' => [
    'enabled' => false,
    // ...
],
```

This removes the Pages resource from your Filament sidebar and disables the public frontend routes.

## Step 4: Run migrations

```bash
php artisan migrate
```

The package's migrations create two tables (`layup_pages`, `layup_page_revisions`). Even with `pages.enabled = false`, running these migrations is recommended -- the tables sit empty and cost nothing, and you can flip `pages.enabled` back on later without re-migrating.

## Step 5: Publish Filament assets and storage symlink

```bash
php artisan filament:assets
php artisan storage:link
```

`filament:assets` registers the builder's CSS. `storage:link` makes uploaded images web-accessible (the field uses Laravel's default storage disk for media uploads).

## Step 6: Generate the Tailwind safelist

```bash
php artisan layup:safelist
```

The field generates dynamic CSS classes (column widths, spacing, user-defined design tokens). Tailwind needs to know about them. Configure your Tailwind setup exactly as documented in the main install guide -- the steps are identical:

- [Tailwind v4 setup](installation.md#tailwind-v4)
- [Tailwind v3 setup](installation.md#tailwind-v3)
- [Build pipeline integration](installation.md#build-pipeline-integration)

When `pages.enabled = false`, the auto-sync hook does not fire on saves (it is wired to the Page model). Run `php artisan layup:safelist` from your build pipeline, or hook a model event on whatever model holds your Layup content -- see [Embedding the field](embedding-the-field.md#tailwind-safelist-still-applies).

## Step 7: Verify

```bash
php artisan layup:doctor
```

The health check warns about the Pages resource being disabled -- that warning is expected and safe to ignore on this path.

## Next: embed the field

You are ready to add `LayupBuilder` to your own forms. See [Embedding the field](embedding-the-field.md) for the model + form + render walkthrough.
