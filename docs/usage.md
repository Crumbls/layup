---
title: Usage
nav_title: Usage
weight: 30
order: 30
---

# Usage

Layup has two supported usage modes: the bundled Pages CMS and the field-only builder.

## Use the bundled Pages resource

After `LayupPlugin::make()` is registered and `pages.enabled` is `true`, Layup registers its Page resource in the Filament panel.

Editors can create a page, arrange content with rows and widgets, set SEO metadata, and save the page as `draft`, `scheduled`, or `published`.

Published pages are visible only when `status` is `published` and `published_at` is empty or in the past. If a page is saved as `published` with a future `published_at`, the `Crumbls\Layup\Models\Page` model reclassifies it as `scheduled`. The `layup:publish-scheduled` command promotes due pages back to `published`.

## Use Layup as a field

Add a JSON column to your own model and cast it to an array:

```php
protected function casts(): array
{
    return [
        'content' => 'array',
    ];
}
```

Add the builder to a Filament form:

```php
use Crumbls\Layup\Forms\Components\LayupBuilder;

LayupBuilder::make('content')
    ->columnSpanFull();
```

Render the content in Blade:

```blade
@layup($post->content)
```

For a complete model, migration, resource, and rendering walkthrough, see [Embedding the field](embedding-the-field.md).

## Render page content

The bundled `Page` model uses `Crumbls\Layup\Concerns\HasLayupContent`, so it can render itself:

```php
$html = $page->toHtml();
```

In Blade, use the directive when you already have the content array:

```blade
@layup($page->content)
```

Use the component when you need to render one widget by type:

```blade
<x-layup-widget type="heading" :data="['content' => 'Hello']" />
```

## Manage widgets

```bash
php artisan layup:list-widgets
php artisan layup:make-widget FeatureCalloutWidget --with-test
php artisan layup:debug-widget heading --data='{"content":"Hello","level":"h2"}'
```

Generated widgets are placed under `App\Layup\Widgets` by default and are discovered through the `widget_discovery` config.

## Keep Tailwind classes available

Layup stores responsive layout and design choices as classes. Generate the safelist before Tailwind compiles:

```bash
php artisan layup:safelist
```

In Tailwind v4, include the generated file from your CSS entry point:

```css
@source "../../storage/layup-safelist.txt";
```

In Tailwind v3, include `./storage/layup-safelist.txt` in the `content` array.

## Export and import pages

```bash
php artisan layup:export --status=published --output=storage/app/layup-pages.json --pretty
php artisan layup:import storage/app/layup-pages.json --dry-run
php artisan layup:import storage/app/layup-pages.json --overwrite
```
