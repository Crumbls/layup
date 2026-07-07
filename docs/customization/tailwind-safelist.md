---
title: Tailwind Safelist
weight: 3
nav_title: Tailwind Safelist
order: 30
---

Layup generates CSS classes dynamically based on page content (column widths, user-added classes, theme utilities). Tailwind's purge process cannot detect these classes in static analysis, so Layup generates a safelist file.

## How it works

The `SafelistCollector` gathers classes from three sources:

1. **Static classes** -- predictable utility classes for column widths, gaps, and flex properties (loaded from `resources/css/safelist-classes.txt`)
2. **Dynamic classes** -- user-added CSS classes from the Advanced tab of any widget, row, or column
3. **Extra classes** -- additional classes defined in `config('layup.safelist.extra_classes')`

All published pages are scanned. The result is written to a text file (one class per line).

## Configuration

```php
'safelist' => [
    'enabled' => true,
    'auto_sync' => true,
    'path' => 'storage/layup-safelist.txt',
    'extra_classes' => [],
],
```

- **auto_sync** -- when `true`, the safelist regenerates on every page save and delete
- **path** -- where the safelist file is written (relative to project root)
- **extra_classes** -- classes to always include, regardless of page content

## Generating manually

```bash
php artisan layup:safelist
```

## Integrating with Tailwind

### Tailwind v4

Add to your CSS file:

```css
@source "../../storage/layup-safelist.txt";
```

### Tailwind v3

Add to `tailwind.config.js`:

```js
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './storage/layup-safelist.txt',
    ],
};
```

## The SafelistChanged event

When `SafelistCollector::sync()` detects changes in the class list, it dispatches the `Crumbls\Layup\Events\SafelistChanged` event. The event exposes:

- `$added` -- classes newly added to the safelist
- `$removed` -- classes no longer present
- `$path` -- absolute path to the safelist file

Listen for this when you need to rebuild assets or invalidate CDN caches in response to content changes.

### Trigger a CI rebuild

```php
namespace App\Listeners;

use Crumbls\Layup\Events\SafelistChanged;
use Illuminate\Support\Facades\Http;

class RebuildFrontendAssets
{
    public function handle(SafelistChanged $event): void
    {
        if ($event->added === [] && $event->removed === []) {
            return;
        }

        Http::post(config('services.ci.deploy_hook'), [
            'reason' => 'Layup safelist changed',
            'added' => count($event->added),
            'removed' => count($event->removed),
        ]);
    }
}
```

Register the listener in your `EventServiceProvider`:

```php
protected $listen = [
    \Crumbls\Layup\Events\SafelistChanged::class => [
        \App\Listeners\RebuildFrontendAssets::class,
    ],
];
```

### Invalidate a CDN cache

```php
public function handle(SafelistChanged $event): void
{
    cache()->forget('frontend-css-version');

    // Or post to your CDN's purge API:
    Http::withToken(config('services.cloudflare.token'))
        ->post('https://api.cloudflare.com/client/v4/zones/.../purge_cache', [
            'files' => [config('app.url') . '/build/assets/app.css'],
        ]);
}
```

The event fires from a synchronous code path -- if your handler does I/O, dispatch a job:

```php
public function handle(SafelistChanged $event): void
{
    \App\Jobs\PurgeAssetCdnCache::dispatch($event->added, $event->removed);
}
```

## Using SafelistCollector directly

```php
use Crumbls\Layup\Support\SafelistCollector;

// All classes from all published pages
$classes = SafelistCollector::classes();

// Classes from specific pages
$classes = SafelistCollector::classesForPages($pages);

// Classes from a raw content array
$classes = SafelistCollector::classesFromContent($page->content);

// Static plugin classes only
$classes = SafelistCollector::staticClasses();

// Sync and write the safelist file
SafelistCollector::sync();
```
