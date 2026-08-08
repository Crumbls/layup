---
title: Field-only Installation
weight: 70
nav_title: Field-only Installation
order: 70
---

# Field-only installation

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

## Step 3: Publish the config and disable Pages features

```bash
php artisan vendor:publish --tag=layup-config
```

Open `config/layup.php` and disable both the Pages resource and its bundled frontend routes:

```php
// config/layup.php
'pages' => [
    'enabled' => false,
    // ...
],

'frontend' => [
    'enabled' => false,
    // ...
],
```

`pages.enabled` removes the Pages resource from your Filament sidebar. `frontend.enabled` disables the bundled `/pages/*` routes. Keep the frontend routes enabled only when you deliberately want to render records from Layup's bundled Page model.

## Step 4: Run your application migrations as usual

```bash
php artisan migrate
```

Layup does not load its Pages migrations while `pages.enabled` is `false`, so this command does not create `layup_pages` or `layup_page_revisions`. Your model's own JSON-column migration remains the only database work required for this path.

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

The field generates dynamic CSS classes (column widths, spacing, and user-defined classes). Tailwind needs to know about them. Configure your host models as safelist sources, then configure Tailwind exactly as documented in the main install guide:

```php
// config/layup.php
'safelist' => [
    'content_sources' => [
        ['model' => App\Models\Post::class, 'column' => 'content'],
    ],
],
```

- [Tailwind v4 setup](installation.md#tailwind-v4)
- [Tailwind v3 setup](installation.md#tailwind-v3)
- [Build pipeline integration](installation.md#build-pipeline-integration)

When `pages.enabled = false`, hook safelist syncing into every model listed in `content_sources`. See [Embedding the field](embedding-the-field.md#tailwind-safelist-still-applies).

## Step 7: Verify

```bash
php artisan layup:doctor
npm run build
```

The health check should report that the bundled Pages migrations are not required. It also verifies each configured safelist content source. Rebuilding assets after the safelist is configured ensures that the editor's dynamic classes are available on the frontend.

## Next: embed the field

You are ready to add `LayupBuilder` to your own forms. Complete [Embedding the field](embedding-the-field.md), then save a record with a widget and render it in a Blade view to verify your application's end-to-end path.
