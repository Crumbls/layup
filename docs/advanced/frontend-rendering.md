---
title: Frontend Rendering
weight: 6
---

Layup includes an optional frontend controller that serves published pages with SEO metadata. This page covers the layout requirements, routing options, custom controllers, and Alpine.js integration.

## Layout requirements

The `layout` config value is a Blade component name passed to `<x-dynamic-component>`:

- `'app'` resolves to `resources/views/components/app.blade.php`
- `'layouts.app'` resolves to `resources/views/components/layouts/app.blade.php`

Your layout must accept a `title` slot and optionally a `meta` slot:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    {{ $meta ?? '' }}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    {{ $slot }}

    @layupScripts
</body>
</html>
```

Two things are required for interactive widgets to work:

- **Alpine.js** must be loaded. If your JS entry point does not import Alpine, interactive widgets (accordion, tabs, countdown, etc.) will render but will not respond to clicks.
- **`@layupScripts`** registers Layup's Alpine components. Without it, widgets that depend on custom Alpine data will not function.

## Serving pages at the site root

Set the prefix to an empty string to serve pages directly at `/`:

```php
'frontend' => [
    'prefix' => '',
],
```

Layup automatically excludes Filament panel paths, Livewire, and other framework routes. Add custom exclusions with `excluded_paths`:

```php
'frontend' => [
    'prefix' => '',
    'excluded_paths' => ['blog', 'shop'],
],
```

## Nested slugs

Pages support nested slugs via wildcard routing:

```
/pages/about          -> slug: about
/pages/about/team     -> slug: about/team
```

## Default page (homepage)

Set `default_slug` to serve a specific page at the index route:

```php
'pages' => [
    'default_slug' => 'home',
],
```

| URL | Resolves |
|-----|----------|
| `/pages` (or `/` with empty prefix) | Page with slug `home` |
| `/pages/about` | Page with slug `about` |

## Custom controllers

Layup provides `AbstractController` as a base for custom frontend controllers:

```
AbstractController      -> Base (returns any Eloquent Model)
  PageController        -> Built-in slug-based lookup
```

Scaffold one:

```bash
php artisan layup:make-controller PageController
```

Or create manually:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Crumbls\Layup\Http\Controllers\AbstractController;
use Crumbls\Layup\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PageController extends AbstractController
{
    protected function getRecord(Request $request): Model
    {
        return Page::published()
            ->where('slug', $request->route('slug'))
            ->firstOrFail();
    }
}
```

Works with any model that uses `HasLayupContent`:

```php
use App\Models\Post;

class PostController extends AbstractController
{
    protected function getRecord(Request $request): Model
    {
        return Post::where('slug', $request->route('slug'))
            ->firstOrFail();
    }
}
```

Register the route and disable built-in routes:

```php
// routes/web.php
Route::get('/{slug}', PageController::class)->where('slug', '.*');

// config/layup.php
'frontend' => ['enabled' => false],
```

### Override methods

| Method | Purpose | Default |
|--------|---------|---------|
| `getRecord(Request $request): Model` | **Required.** Resolve the model. | (abstract) |
| `authorize(Request $request, Model $record): void` | Gate access. Throw/abort to deny. | No-op |
| `getLayout(Request $request, Model $record): string` | Blade layout component name. | `config('layup.frontend.layout')` |
| `getView(Request $request, Model $record): string` | Blade view to render. | `config('layup.frontend.view')` |
| `getViewData(Request $request, Model $record, array $sections): array` | Extra variables merged into view data. | `[]` |
| `getCacheTtl(Request $request, Model $record): ?int` | Seconds for `Cache-Control` header. | `null` |

### View variables

| Variable | Type | Description |
|----------|------|-------------|
| `$page` | `Model` | The resolved record (also available as `$record`) |
| `$record` | `Model` | Same as `$page` |
| `$sections` | `array` | Section tree with hydrated Row/Column/Widget objects |
| `$tree` | `array` | Flat list of Row objects (all sections merged) |
| `$layout` | `string` | Layout component name |

### Example: authorized pages

```php
class MemberPageController extends AbstractController
{
    protected function getRecord(Request $request): Model
    {
        return Page::published()
            ->where('slug', $request->route('slug'))
            ->firstOrFail();
    }

    protected function authorize(Request $request, Model $record): void
    {
        abort_unless($request->user(), 403);
    }

    protected function getLayout(Request $request, Model $record): string
    {
        return 'layouts.member-area';
    }
}
```

### Example: cached pages

```php
class CachedPageController extends AbstractController
{
    protected function getRecord(Request $request): Model
    {
        return Page::published()
            ->where('slug', $request->route('slug'))
            ->firstOrFail();
    }

    protected function getCacheTtl(Request $request, Model $record): ?int
    {
        return 300; // 5 minutes
    }
}
```

## Frontend scripts

Layup's interactive widgets use Alpine.js components. By default, they are inlined via `@layupScripts`.

### Bundle yourself

Disable auto-include and import in your build:

```php
// config/layup.php
'frontend' => ['include_scripts' => false],
```

```js
// resources/js/app.js
import '../../vendor/crumbls/layup/resources/js/layup.js'
```

Or publish and customize:

```bash
php artisan vendor:publish --tag=layup-scripts
```

### Available Alpine components

| Component | Widget | Parameters |
|-----------|--------|------------|
| `layupAccordion` | Accordion | `(openFirst = true)` |
| `layupToggle` | Toggle | `(open = false)` |
| `layupTabs` | Tabs | none |
| `layupCountdown` | Countdown | `(targetDate)` |
| `layupSlider` | Slider | `(total, autoplay, speed)` |
| `layupCounter` | Number Counter | `(target, animate)` |
| `layupBarCounter` | Bar Counter | `(percent, animate)` |
| `layupLightbox` | Gallery | none |
