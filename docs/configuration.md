---
title: Configuration
weight: 5
---

Publish the config file with:

```bash
php artisan vendor:publish --tag=layup-config
```

This creates `config/layup.php`. Below is every configuration option.

## Widgets

```php
'widgets' => [
    \Crumbls\Layup\View\TextWidget::class,
    \Crumbls\Layup\View\HeadingWidget::class,
    // ... 95+ built-in widget classes
],
```

The full list of registered widgets. Remove entries to disable specific widgets, or add your own classes.

## Widget auto-discovery

```php
'widget_discovery' => [
    'namespace' => 'App\\Layup\\Widgets',
    'directory' => null, // defaults to app_path('Layup/Widgets')
],
```

Layup automatically discovers widget classes in this namespace. Any class extending `BaseWidget` found here is registered without needing to add it to the `widgets` array.

## Uploads

```php
'uploads' => [
    'disk' => 'public',
],
```

The filesystem disk used for media uploads (images, files). Must be publicly accessible.

## Pages

```php
'pages' => [
    'table' => 'layup_pages',
    'model' => \Crumbls\Layup\Models\Page::class,
    'default_slug' => null,
],
```

- **table** -- database table name for pages. Change this if you need multiple Layup instances with separate tables.
- **model** -- the Eloquent model class. Extend `Page` and point here to add custom behavior.
- **default_slug** -- if set, this slug is served when the frontend prefix is hit without a slug.

## Revisions

```php
'revisions' => [
    'enabled' => true,
    'max' => 50,
],
```

- **enabled** -- toggle automatic revision creation on page save
- **max** -- maximum revisions to keep per page. Older revisions are pruned automatically.

## Frontend

```php
'frontend' => [
    'enabled' => true,
    'prefix' => 'pages',
    'middleware' => ['web'],
    'domain' => null,
    'layout' => 'app',
    'view' => 'layup::frontend.page',
    'max_width' => 'container',
    'include_scripts' => true,
    'excluded_paths' => [],
],
```

- **enabled** -- register frontend routes for serving published pages
- **prefix** -- URL prefix (e.g., `pages` makes URLs like `/pages/about`)
- **middleware** -- middleware applied to frontend routes
- **domain** -- restrict routes to a specific domain
- **layout** -- Blade component used as the page layout (e.g., `app` for `<x-app>`)
- **view** -- Blade view for rendering pages
- **max_width** -- CSS class for page container width
- **include_scripts** -- include Alpine.js animation scripts in frontend
- **excluded_paths** -- slug patterns to exclude from Layup routing

## Safelist

```php
'safelist' => [
    'enabled' => true,
    'auto_sync' => true,
    'path' => 'storage/layup-safelist.txt',
    'extra_classes' => [],
],
```

- **enabled** -- toggle safelist generation
- **auto_sync** -- regenerate the safelist file automatically on page save/delete
- **path** -- output path for the safelist file (relative to project root)
- **extra_classes** -- additional CSS classes to always include in the safelist

## Breakpoints

```php
'breakpoints' => [
    'sm' => ['label' => 'sm', 'width' => 640, 'icon' => 'heroicon-o-device-phone-mobile'],
    'md' => ['label' => 'md', 'width' => 768, 'icon' => 'heroicon-o-device-tablet'],
    'lg' => ['label' => 'lg', 'width' => 1024, 'icon' => 'heroicon-o-computer-desktop'],
    'xl' => ['label' => 'xl', 'width' => 1280, 'icon' => 'heroicon-o-tv'],
],

'default_breakpoint' => 'lg',
```

Defines the responsive breakpoints available in the builder's preview toolbar and the SpanPicker. The `default_breakpoint` sets which view is shown by default when editing.

## Row templates

```php
'row_templates' => [
    [12],
    [6, 6],
    [4, 4, 4],
    [3, 3, 3, 3],
    [8, 4],
    [4, 8],
    [3, 6, 3],
    [2, 8, 2],
],
```

Predefined column layouts shown when adding a new row. Each array represents column spans that should sum to 12. Add custom layouts here.
