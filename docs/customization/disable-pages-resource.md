---
title: Disable the Pages Resource
weight: 3
---

The bundled Pages resource adds a `Pages` item to your Filament sidebar with full CRUD, nested pages, scheduled publishing, and frontend routes. Disable it when you only want the `LayupBuilder` field on your own models.

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

## What disappears

| Feature | Status when disabled |
|---|---|
| `Pages` resource in Filament sidebar | Removed |
| Public frontend routes (`/pages/*`) | Not registered |
| Scheduled publishing command | Still registered, but has nothing to act on |
| `Page` model | Still loadable -- just no UI or routes use it |
| `layup_pages` and `layup_page_revisions` tables | Still created if you ran migrations |

The widget registry, theme system, `LayupBuilder` field, Tailwind safelist generation, and Blade directives all keep working. Disabling the Pages resource only removes the bundled CMS UI -- not the underlying primitives.

## What still works

- `LayupBuilder::make('content')` in any Filament form
- `@layup($model->content)` rendering
- The `HasLayupContent` trait on any model
- The `<x-layup-widget>` Blade component
- All `php artisan layup:*` commands (`safelist`, `make-widget`, `doctor`, etc.)
- Theme overrides via the plugin API

## Migration cleanup (optional)

The package's migrations create `layup_pages` and `layup_page_revisions` tables even when `pages.enabled = false`. The tables sit empty and cost nothing -- leave them in place if there is any chance you might enable the Pages resource later.

If you are certain you will never use the Pages resource and want a strictly clean schema, you can write a migration in your own application that drops the tables:

```php
public function up(): void
{
    Schema::dropIfExists('layup_page_revisions');
    Schema::dropIfExists('layup_pages');
}
```

Run that **after** the package migrations, and treat it as a one-way decision -- enabling the Pages resource later will require recreating the tables manually.

## Related guides

- [Field-only installation](/documentation/layup/v1/field-only-installation) -- the minimal install path that disables the Pages resource from the start
- [Embedding the field](/documentation/layup/v1/embedding-the-field) -- using `LayupBuilder` on your own models
- [Swapping the Page model](swapping-the-page-model.md) -- if you do want the resource but with a custom model
