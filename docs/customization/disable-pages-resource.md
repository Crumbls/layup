---
title: Disable the Pages Resource
weight: 3
nav_title: Disable the Pages Resource
order: 30
---

The bundled Pages resource adds a `Pages` item to your Filament sidebar with full CRUD and scheduled publishing. Disable it when you only want the `LayupBuilder` field on your own models.

## Configure

Set `pages.enabled` to `false` in `config/layup.php`:

```php
// config/layup.php
'pages' => [
    'enabled' => false,
    // ... other keys are ignored when enabled is false
],
```

Restart any running Filament panels (or clear the route cache if you are caching routes).

To disable the bundled `/pages/*` frontend routes as well, set `frontend.enabled` to `false`. The [field-only installation](../field-only-installation.md) guide disables both settings.

## What disappears

| Feature | Status when disabled |
|---|---|
| `Pages` resource in Filament sidebar | Removed |
| Public frontend routes (`/pages/*`) | Unchanged; disable separately with `frontend.enabled = false` |
| Scheduled publishing command | Still registered, but has nothing to act on |
| `Page` model | Still loadable -- just no UI or routes use it |
| `layup_pages` and `layup_page_revisions` tables | Existing tables are unchanged; new migrations are not loaded while disabled |

The widget registry, theme system, `LayupBuilder` field, Tailwind safelist generation, and Blade directives all keep working. Disabling the Pages resource only removes the bundled CMS UI -- not the underlying primitives.

## What still works

- `LayupBuilder::make('content')` in any Filament form
- `@layup($model->content)` rendering
- The `HasLayupContent` trait on any model
- The `<x-layup-widget>` Blade component
- All `php artisan layup:*` commands (`safelist`, `make-widget`, `doctor`, etc.)
- Theme overrides via the plugin API

## Existing database tables

Existing Pages tables remain available after you disable the resource. On a new installation, Layup does not load Pages migrations when `pages.enabled` is `false`.

If you no longer need existing Pages data and are certain you will never re-enable the resource, you can remove those tables with an application migration:

```php
public function up(): void
{
    Schema::dropIfExists('layup_page_revisions');
    Schema::dropIfExists('layup_pages');
}
```

Treat this as a one-way decision -- re-enabling the Pages resource later requires recreating the tables through a migration.

## Related guides

- [Field-only installation](../field-only-installation.md) -- the minimal install path that disables the Pages resource from the start
- [Embedding the field](../embedding-the-field.md) -- using `LayupBuilder` on your own models
- [Swapping the Page model](swapping-the-page-model.md) -- if you do want the resource but with a custom model
