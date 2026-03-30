# Layup

A visual page builder plugin for [Filament](https://filamentphp.com). Divi-style editor with rows, columns, and 95 extensible widgets — all using native Filament form components.

![Layup Showcase](layup-showcase.jpg)

## Features

- **Flex-based 12-column grid** with responsive breakpoints (sm/md/lg/xl)
- **Visual span picker** — click-to-set column widths per breakpoint
- **Drag & drop** — reorder widgets and rows
- **Undo/Redo** — Ctrl+Z / Ctrl+Shift+Z with full history stack
- **Widget picker modal** — searchable, categorized, grid layout
- **Three-tab form schema** — Content / Design / Advanced on every component
- **Full Design tab** — text color, alignment, font size, border, border radius, box shadow, opacity, background color, padding, margin
- **Responsive visibility** — show/hide per breakpoint on any element
- **Entrance animations** — fade in, slide up/down/left/right, zoom in (via Alpine x-intersect)
- **Frontend rendering** — configurable routes, layouts, and SEO meta (OG, Twitter Cards, canonical, JSON-LD)
- **Tailwind safelist** — automatic class collection for dynamic content
- **Page templates** — 5 built-in templates (blank, landing, about, contact, pricing) + save your own
- **Content revisions** — auto-save on content change, configurable max, restore from history
- **Export / Import** — pages as JSON files
- **Widget lifecycle hooks** -- `onSave`, `onCreate`, `onDelete`, `onDuplicate` with optional context
- **Content validation** -- structural + widget type validation
- **Form Field Packs** -- reusable field groups for image, link, and color patterns
- **`prepareForRender()` hook** -- transform widget data before rendering
- **Widget validation rules** -- self-declaring validation via `getValidationRules()`
- **Widget deprecation** -- graceful sunset path with `isDeprecated()`
- **`WidgetData` value object** -- typed accessors for Blade views
- **Widget debug command** -- `layup:debug-widget` for instant widget introspection
- **Render isolation** -- broken widgets no longer crash entire pages
- **Widget search tags** -- additional terms for the builder's widget picker
- **Widget asset declaration** -- declare JS/CSS dependencies per widget
- **Widget auto-discovery** — scans `App\Layup\Widgets` for custom widgets
- **Global theme system** — CSS custom properties for colors, fonts, and border radius with Filament panel inheritance
- **Dark mode theme support** -- automatic color lightening with manual overrides
- **Configurable model** — swap the Page model per dashboard
- **`HasLayupContent` trait** -- add Layup rendering to any Eloquent model
- **`<x-layup-widget>` component** -- render individual widgets in any Blade template
- **Testing helpers** -- factory states and assertions for custom widget development
- **Developer tooling** -- `layup:doctor`, `layup:list-widgets`, `layup:search` commands
- **Publishable stubs** -- customize `make-widget` scaffolding templates
- **1,131 tests, 3,517 assertions**

### Built-in Widgets (95)

| Category | Widgets |
|----------|---------|
| **Content** (57) | Text, Heading, Rich Text, Blurb, Icon, Icon Box, Icon List, Badge, Card, Alert, List, Blockquote, Banner, Section Heading, Accordion, Toggle, Tabs, Feature List, Feature Grid, Testimonial, Testimonial Carousel, Testimonial Grid, Testimonial Slider, Breadcrumbs, Person, Step Process, Team Grid, Logo Grid, Logo Slider, Avatar Group, Price, Metric, Social Proof, Image Text, Text Columns, Timeline, Animated Heading, Bar Counter, Highlight Box, Number Counter, Star Rating, Gradient Text, Typewriter, Quote Carousel, Marquee, Table of Contents, Stat Card, Changelog, Menu, Notification Bar, Table, Comparison Table, Skill Bar, Progress Circle, Post List, Hero, FAQ (with JSON-LD) |
| **Media** (13) | Image (with hover effects), Gallery (with lightbox + captions), Video, Video Playlist, Audio, Slider, Masonry, Lottie, Map, Before/After, Image Card, Hotspot, Image Hotspot |
| **Interactive** (18) | Button (hover colors), Call to Action, CTA Banner, Countdown, Pricing Table, Pricing Toggle, Social Follow, Search, Contact Form, Login, Newsletter, Modal, Flip Card, Cookie Consent, Content Toggle, Share Buttons, File Download, Back to Top |
| **Layout** (4) | Spacer, Divider, Separator, Anchor |
| **Advanced** (3) | HTML, Code Block, Embed |

## Requirements

- PHP 8.3+
- Laravel 12+
- Filament 5
- Livewire 4

## Installation

### Prerequisites

Layup requires a working Filament installation. If you haven't set up Filament yet, install it first:

```bash
composer require filament/filament
php artisan filament:install --panels
```

This creates a panel provider at `app/Providers/Filament/AdminPanelProvider.php`. If you already have a Filament panel set up, skip this step.

See the [Filament installation docs](https://filamentphp.com/docs/panels/installation) for details.

### Install Layup

**1. Require the package:**

```bash
composer require crumbls/layup
```

**2. Register the plugin in your Filament panel provider:**

Open your panel provider (e.g. `app/Providers/Filament/AdminPanelProvider.php`) and add the Layup plugin:

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

> Without this step, the "Pages" resource will not appear in your Filament sidebar. There is no error -- it simply won't show up.

**3. Run the install command:**

```bash
php artisan layup:install
```

This handles everything in one step:

- Publishes the config file
- Runs database migrations (creates `layup_pages` and `layup_page_revisions` tables)
- Creates a storage symlink (`storage:link`) so uploaded images are web-accessible
- Creates the frontend layout component if it doesn't exist
- Publishes Filament assets (CSS)
- Generates the Tailwind safelist
- Checks your setup and warns about any issues

**4. Add the safelist to your Tailwind config:**

Tailwind v4 (`resources/css/app.css`):
```css
@source "../../storage/layup-safelist.txt";
```

Tailwind v3 (`tailwind.config.js`):
```js
content: ['./storage/layup-safelist.txt']
```

**5. Rebuild your frontend assets:**

```bash
npm run build
# or: bun run build / pnpm run build / yarn build
```

> Without steps 4 and 5, the page builder will work in the admin panel but frontend pages will have broken or missing styling.

**6. Create and publish a page:**

Visit your Filament panel and create a page. New pages default to **draft** status -- they will not appear on the frontend until you set the status to **published**. If you visit a page URL and get a 404, check the status first.

### Quick Verification

After installation, run the diagnostic command to check for any remaining issues:

```bash
php artisan layup:doctor
```

Then visit `/admin/pages` (or your panel path) and create a new page. You should see the visual builder with rows, columns, and the widget picker.

### Manual Installation

If you prefer to run each step yourself instead of using `layup:install`:

```bash
php artisan vendor:publish --tag=layup-config
php artisan migrate
php artisan storage:link
php artisan filament:assets
php artisan layup:safelist
```

## Frontend Rendering

Layup includes an optional frontend controller that serves pages at a configurable URL prefix.

### Enable Frontend Routes

In `config/layup.php`:

```php
'frontend' => [
    'enabled' => true,
    'prefix'  => 'pages',       // → yoursite.com/pages/{slug}
    'middleware' => ['web'],
    'domain'  => null,           // Restrict to a specific domain
    'layout'  => 'app',         // Blade component layout
    'view'    => 'layup::frontend.page',
],
```

The `layout` value is passed to `<x-dynamic-component>`, so it should be a Blade component name. For example:

- `'app'` → `resources/views/components/app.blade.php`
- `'layouts.app'` → `resources/views/components/layouts/app.blade.php`
- `'app-layout'` → `App\View\Components\AppLayout`

Your layout must accept a `title` slot and optionally a `meta` slot for SEO tags:

```blade
{{-- resources/views/components/app.blade.php --}}
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

- **Alpine.js** must be loaded. A standard Laravel starter bundles it in `resources/js/app.js`. If your JS entry point doesn't import Alpine, interactive widgets (cookie consent, accordion, tabs, countdown, etc.) will render but won't respond to clicks.
- **`@layupScripts`** registers Layup's Alpine components. Without it, widgets that depend on custom Alpine data (accordion, tabs, slider, counters) won't function. There is no error in either case -- things just silently don't work.

### Serving Pages at the Site Root

To serve pages directly at `/` instead of `/pages`, set the prefix to an empty string:

```php
'frontend' => [
    'prefix' => '',
],
```

Layup automatically excludes Filament panel paths, Livewire, and other common framework routes so the catch-all doesn't shadow them. If you have custom routes that conflict, add them to `excluded_paths`:

```php
'frontend' => [
    'prefix' => '',
    'excluded_paths' => ['blog', 'shop'],
],
```

### Nested Slugs

Pages support nested slugs via wildcard routing:

```
/pages/about          → slug: about
/pages/about/team     → slug: about/team
```

### Default Page (Homepage)

By default, hitting the index route (`/pages` or `/` with an empty prefix) looks for a page with an empty slug. To serve a specific page as the homepage instead, set `default_slug` in your config:

```php
// config/layup.php
'pages' => [
    'table' => 'layup_pages',
    'model' => \Crumbls\Layup\Models\Page::class,
    'default_slug' => 'home',  // Serve the "home" page at the index route
],
```

With this configuration:

| URL | Resolves |
|-----|----------|
| `/pages` (or `/` with empty prefix) | Page with slug `home` |
| `/pages/about` | Page with slug `about` |
| `/pages/docs/getting-started` | Page with slug `docs/getting-started` |

Set `default_slug` to `null` to use the original behavior (looks for a page with an empty slug).

This works with any prefix configuration -- whether you serve pages at `/pages/{slug}` or directly at `/{slug}` with an empty prefix.

### Custom Controller

Layup provides a base controller for frontend rendering:

```
AbstractController      → Base (returns any Eloquent Model)
  └─ PageController     → Built-in slug-based lookup (ships with Layup)
```

Extend `AbstractController` to render any model that implements `getSectionTree()` and `getContentTree()`.

**Scaffold a controller:**

```bash
php artisan layup:make-controller PageController
```

Or create one manually:

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

Works with any model, not just Page:

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

After creating your controller:

1. Register the route in `routes/web.php`:

    ```php
    use App\Http\Controllers\PageController;

    Route::get('/{slug}', PageController::class)->where('slug', '.*');
    ```

2. Disable the built-in routes in `config/layup.php`:

    ```php
    'frontend' => [
        'enabled' => false,
    ],
    ```

3. Set your layout component in `config/layup.php`:

    ```php
    'frontend' => [
        'layout' => 'app',
    ],
    ```

### Override Methods

`AbstractController` provides these methods your IDE will autocomplete. Override any of them to customize behavior:

| Method | Purpose | Default |
|--------|---------|---------|
| `getRecord(Request $request): Model` | **Required.** Resolve the model. | (abstract) |
| `authorize(Request $request, Model $record): void` | Gate access. Throw/abort to deny. | No-op |
| `getLayout(Request $request, Model $record): string` | Blade layout component name. | `config('layup.frontend.layout')` |
| `getView(Request $request, Model $record): string` | Blade view to render. | `config('layup.frontend.view')` |
| `getViewData(Request $request, Model $record, array $sections): array` | Extra variables merged into view data. | `[]` |
| `getCacheTtl(Request $request, Model $record): ?int` | Seconds for `Cache-Control` header. `null` to skip. | `null` |

Your `getRecord()` returns `Model`, so you can return `Page`, a custom subclass, or any model with the right methods.

### View Variables

The following variables are available in the rendered Blade view:

| Variable | Type | Description |
|----------|------|-------------|
| `$page` | `Model` | The resolved record (also available as `$record`) |
| `$record` | `Model` | Same as `$page` (alias for non-Page models) |
| `$sections` | `array` | Section tree with hydrated Row/Column/Widget objects |
| `$tree` | `array` | Flat list of Row objects (all sections merged) |
| `$layout` | `string` | Layout component name |

Plus any additional variables returned by `getViewData()`.

### Example: Authorized Pages with Custom Layout

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

    protected function getViewData(Request $request, Model $record, array $sections): array
    {
        return [
            'user' => $request->user(),
        ];
    }
}
```

### Example: Cached Public Pages

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

### Example: View Fallback Chain

```php
class ThemePageController extends AbstractController
{
    protected function getRecord(Request $request): Model
    {
        return Page::published()
            ->where('slug', $request->route('slug'))
            ->firstOrFail();
    }

    protected function getView(Request $request, Model $record): string
    {
        $slugView = 'pages.' . str_replace('/', '.', $record->slug);

        if (view()->exists($slugView)) {
            return $slugView;
        }

        return parent::getView($request, $record);
    }
}
```

## Tailwind CSS Integration

Layup generates Tailwind utility classes dynamically — column widths like `w-6/12`, `md:w-3/12`, gap values, and any custom classes users add via the Advanced tab. Since Tailwind scans source files (not databases), these classes need to be safelisted.

### How It Works

Layup provides two layers of class collection:

1. **Static classes** — Every possible Tailwind utility the plugin can generate (column widths × 4 breakpoints, flex utilities, gap values). These are finite and ship with the package.
2. **Dynamic classes** — Custom classes users type into the "CSS Classes" field on any row, column, or widget's Advanced tab.

Both are merged into a single safelist file.

### Quick Setup

**1. Generate the safelist file:**

```bash
php artisan layup:safelist
```

This writes `storage/layup-safelist.txt` with all classes (static + from published pages).

**2. Add to your CSS (Tailwind v4):**

```css
/* resources/css/app.css */
@import "tailwindcss";
@source "../../storage/layup-safelist.txt";
```

**3. Build:**

```bash
npm run build
```

That's it. All Layup classes will be included in your compiled CSS.

### Tailwind v3

If you're on Tailwind v3, add the safelist file to your `tailwind.config.js`:

```js
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './storage/layup-safelist.txt',
    ],
    // ...
}
```

### Build Pipeline Integration

Add the safelist command to your build script so it always runs before Tailwind compiles:

```json
{
    "scripts": {
        "build": "php artisan layup:safelist && vite build"
    }
}
```

Or in a deploy script:

```bash
php artisan layup:safelist
npm run build   # or: bun run build / pnpm run build / yarn build
```

### Auto-Sync on Save

By default, Layup regenerates the safelist file every time a page is saved. If new classes are detected, it dispatches a `SafelistChanged` event.

```php
'safelist' => [
    'enabled'   => true,   // Enable safelist generation
    'auto_sync' => true,   // Regenerate on page save
    'path'      => 'storage/layup-safelist.txt',
],
```

#### Listening for Changes

Use the `SafelistChanged` event to trigger a rebuild, send a notification, or log the change:

```php
use Crumbls\Layup\Events\SafelistChanged;

class HandleSafelistChange
{
    public function handle(SafelistChanged $event): void
    {
        // $event->added   — array of new classes
        // $event->removed — array of removed classes
        // $event->path    — path to the safelist file

        logger()->info('Layup safelist changed', [
            'added'   => $event->added,
            'removed' => $event->removed,
        ]);

        // Trigger a rebuild, notify the team, etc.
    }
}
```

Register in your `EventServiceProvider`:

```php
protected $listen = [
    \Crumbls\Layup\Events\SafelistChanged::class => [
        \App\Listeners\HandleSafelistChange::class,
    ],
];
```

#### How Change Detection Works

Layup uses Laravel's cache (any driver — file, Redis, database, array) to store a hash of the last known class list. On page save, it regenerates the list, compares the hash, and only dispatches the event if something actually changed.

The safelist file write is **best-effort** — if the filesystem is read-only (serverless, containerized deploys), the write silently fails but the event still fires. You can listen for the event and handle the rebuild however your infrastructure requires.

#### Disabling Auto-Sync

If you don't want safelist regeneration on every save (e.g., in production where you build once at deploy time):

```php
'safelist' => [
    'auto_sync' => false,
],
```

You'll need to run `php artisan layup:safelist` manually or as part of your deploy pipeline.

### Command Options

```bash
# Default: write to storage/layup-safelist.txt
php artisan layup:safelist

# Custom output path
php artisan layup:safelist --output=resources/css/layup-classes.txt

# Print to stdout (pipe to another tool)
php artisan layup:safelist --stdout

# Static classes only (no database query — useful in CI)
php artisan layup:safelist --static-only
```

### What Gets Safelisted

| Source | Classes | Example |
|--------|---------|---------|
| Column widths | `w-{n}/12` × 4 breakpoints | `w-6/12`, `md:w-4/12`, `lg:w-8/12` |
| Flex utilities | `flex`, `flex-wrap` | Always included |
| Gap values | `gap-{0-12}` | `gap-4`, `gap-8` |
| User classes | Anything in the "CSS Classes" field | `my-hero`, `bg-blue-500` |

Widget-specific classes (like `layup-widget-text`, `layup-accordion-item`) are **not** Tailwind utilities — they're styled by Layup's own CSS and don't need safelisting.

## Frontend Scripts

Layup's interactive widgets (accordion, tabs, toggle, countdown, slider, counters) use Alpine.js components. By default, the required JavaScript is inlined automatically via the `@layupScripts` directive.

### Auto-Include (default)

No setup needed. The scripts are injected inline on any page that uses `@layupScripts` (included in the default page view).

```php
// config/layup.php
'frontend' => [
    'include_scripts' => true,  // default
],
```

### Bundle Yourself

If you'd rather include the scripts in your own Vite build (for caching, minification, etc.), disable auto-include and import the file:

```php
// config/layup.php
'frontend' => [
    'include_scripts' => false,
],
```

```js
// resources/js/app.js
import '../../vendor/crumbls/layup/resources/js/layup.js'
```

### Publish and Customize

```bash
php artisan vendor:publish --tag=layup-scripts
```

This copies `layup.js` to `resources/js/vendor/layup.js` where you can modify it.

### Available Alpine Components

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

## Theme

Layup includes a global theme system that outputs CSS custom properties and utility classes on the frontend. Widgets use these variables for colors, fonts, and border radius so your pages stay visually consistent.

### How It Works

The `@layupScripts` directive (already in your layout) outputs a `<style>` block with `:root` custom properties and matching utility classes:

```css
:root {
    --layup-primary: #3b82f6;
    --layup-secondary: #6b7280;
    --layup-accent: #f59e0b;
    --layup-success: #22c55e;
    --layup-warning: #f59e0b;
    --layup-danger: #ef4444;
}
.layup-bg-primary { background-color: var(--layup-primary); }
.layup-text-primary { color: var(--layup-primary); }
.layup-border-primary { border-color: var(--layup-primary); }
.layup-hover-bg-primary:hover { background-color: var(--layup-primary); }
.layup-hover-text-primary:hover { color: var(--layup-primary); }
/* ... same for every color */
```

Widgets reference these as `var(--layup-primary)` in inline styles or `layup-bg-primary` as class names.

### Configuring Colors

By default, Layup inherits colors from your Filament panel. If your panel uses `->colors([...])`, those are picked up automatically with no extra configuration.

To override or extend colors, use the plugin fluent API in your panel provider:

```php
use Crumbls\Layup\LayupPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            LayupPlugin::make()
                ->colors([
                    'primary' => '#e11d48',
                    'secondary' => '#6366f1',
                    'accent' => '#f59e0b',
                ]),
        ]);
}
```

Values you pass to `->colors()` merge with (and override) the inherited panel colors.

### Configuring Fonts

```php
LayupPlugin::make()
    ->fonts([
        'heading' => 'Playfair Display, serif',
        'body' => 'Inter, sans-serif',
    ])
```

This generates `--layup-font-heading` and `--layup-font-body` custom properties, plus `.layup-font-heading` and `.layup-font-body` utility classes.

### Configuring Border Radius

```php
LayupPlugin::make()
    ->borderRadius('0.5rem')
```

Generates `--layup-radius` and a `.layup-rounded` utility class.

### Opting Out of Panel Color Inheritance

If you don't want Layup to pull colors from the Filament panel:

```php
LayupPlugin::make()
    ->withoutPanelColors()
    ->colors([
        'primary' => '#3b82f6',
        'secondary' => '#6b7280',
    ])
```

Without `->colors()` and with `->withoutPanelColors()`, the built-in defaults are used (blue primary, gray secondary, amber accent).

### Full Example

```php
LayupPlugin::make()
    ->colors([
        'primary' => '#e11d48',
        'secondary' => '#6366f1',
        'accent' => '#f59e0b',
        'success' => '#22c55e',
    ])
    ->fonts([
        'heading' => 'Playfair Display, serif',
        'body' => 'Inter, sans-serif',
    ])
    ->borderRadius('0.75rem')
```

### Dark Mode

By default, Layup automatically generates lightened variants of your configured colors for dark mode. No extra configuration is required -- the CSS output includes both a `:root {}` block and a `.dark {}` block, and the lightened variants are applied automatically when the `.dark` class is present on the `<html>` element.

To override specific dark mode colors instead of relying on the auto-lightened variants, use `->darkColors()` on the plugin:

```php
LayupPlugin::make()
    ->colors([
        'primary' => '#e11d48',
        'secondary' => '#6366f1',
    ])
    ->darkColors([
        'primary' => '#fb7185',
    ])
```

In this example, `primary` uses the manually specified value in dark mode while `secondary` falls back to its auto-lightened variant.

The generated CSS output follows this structure:

```css
:root {
    --layup-primary: #e11d48;
    --layup-secondary: #6366f1;
}

.dark {
    --layup-primary: #fb7185;      /* manual override */
    --layup-secondary: #a5b4fc;    /* auto-lightened */
}
```

Widgets that reference `var(--layup-primary)` pick up the correct value for light or dark mode automatically with no additional changes needed.

### Using Theme Variables in Custom Widgets

Reference theme variables in your Blade views:

```blade
{{-- Inline style --}}
<div style="background-color: var(--layup-primary)">...</div>

{{-- Utility class --}}
<button class="layup-bg-primary layup-hover-bg-accent text-white">Click</button>
```

The generated utility classes follow the pattern `layup-{property}-{color}`:

| Class Pattern | CSS |
|---------------|-----|
| `layup-bg-{name}` | `background-color: var(--layup-{name})` |
| `layup-text-{name}` | `color: var(--layup-{name})` |
| `layup-border-{name}` | `border-color: var(--layup-{name})` |
| `layup-hover-bg-{name}:hover` | `background-color: var(--layup-{name})` |
| `layup-hover-text-{name}:hover` | `color: var(--layup-{name})` |
| `layup-font-{name}` | `font-family: var(--layup-font-{name})` |
| `layup-rounded` | `border-radius: var(--layup-radius)` |

### ColorPicker Field

Layup provides a `ColorPicker` form field for use in widget schemas. It renders a button group of swatches sourced from the active `LayupTheme`, plus a "Custom" option that opens a native color input.

```php
use Crumbls\Layup\Forms\Components\ColorPicker;

ColorPicker::make('bg_color')
    ->label('Background')
```

The swatches are automatically populated from the configured theme colors, so they stay in sync with your `->colors()` settings without any additional configuration.

**Override swatches**

Pass your own set of labeled color values to replace the auto-sourced theme swatches:

```php
ColorPicker::make('bg_color')
    ->label('Background')
    ->swatches([
        'Red'  => '#ef4444',
        'Blue' => '#3b82f6',
    ])
```

**Disable the custom color input**

```php
ColorPicker::make('bg_color')
    ->allowCustom(false)
```

All built-in widgets and FieldPacks use `ColorPicker` internally for any color field in their Design tabs, so the field is available by default wherever you build widget schemas.

## Rendering content from a model field

The simplest way is the `@layup` Blade directive:
```blade
@layup($model->field)
```

You can also use the `LayupContent` helper class directly. It implements `Htmlable`:
```php
use Crumbls\Layup\Support\LayupContent;

{{ new LayupContent($model->field) }}
```

### WidgetData in Blade Views

Use the `WidgetData` value object for typed, null-safe access to widget data in Blade views:

```blade
@php $d = \Crumbls\Layup\Support\WidgetData::from($data); @endphp
<h1>{{ $d->string('heading') }}</h1>
<img src="{{ $d->storageUrl('background_image') }}" alt="{{ $d->string('alt') }}" />
@if($d->bool('show_overlay'))
    <div style="opacity: {{ $d->float('overlay_opacity', 0.5) }}"></div>
@endif
```

Available methods: `string()`, `bool()`, `int()`, `float()`, `array()`, `has()`, `storageUrl()`, `url()`, `toArray()`. Implements `ArrayAccess` for backward compatibility.

## Using Layup Content on Any Model

Add the `HasLayupContent` trait to any Eloquent model with a JSON content column:

```php
use Crumbls\Layup\Concerns\HasLayupContent;

class Post extends Model
{
    use HasLayupContent;

    protected string $layupContentColumn = 'body'; // default: 'content'

    protected function casts(): array
    {
        return ['body' => 'array'];
    }
}
```

The trait provides:

| Method | Returns | Description |
|--------|---------|-------------|
| `toHtml()` | `string` | Render content to HTML |
| `getSectionTree()` | `array` | Sections with hydrated Row/Column/Widget objects |
| `getContentTree()` | `array` | Flat list of Row objects |
| `getUsedClasses()` | `array` | Tailwind classes used in content |
| `getUsedInlineStyles()` | `array` | Inline styles used in content |

Render in Blade:

```blade
{!! $post->toHtml() !!}
```

Works with the custom controller pattern -- your `AbstractController` subclass can return any model that uses this trait.

## Rendering Individual Widgets

Use the `<x-layup-widget>` Blade component to render a single widget outside the page builder:

```blade
<x-layup-widget type="button" :data="['label' => 'Sign Up', 'url' => '/register']" />
<x-layup-widget type="testimonial" :data="$testimonialData" />
```

This resolves the widget from the registry, applies Design/Advanced tab defaults, and renders the widget's Blade view. Unknown types render nothing and log a warning.

## Custom Widgets

> **For AI agents and detailed reference:** see [agents.md](agents.md) -- a complete, zero-ambiguity guide for creating widgets without error. Covers every method, Blade integration point, common mistakes, and a full checklist.

Create a widget by extending `Crumbls\Layup\View\BaseWidget`. A widget is two files: a PHP class and a Blade view.

```bash
php artisan layup:make-widget ProductCard --with-test
# Creates:
#   app/Layup/Widgets/ProductCardWidget.php
#   resources/views/components/layup/product-card.blade.php
#   tests/Unit/Layup/ProductCardWidgetTest.php
```

Or create manually:

```php
<?php

declare(strict_types=1);

namespace App\Layup\Widgets;

use Crumbls\Layup\View\BaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;

class ProductCardWidget extends BaseWidget
{
    public static function getType(): string { return 'product-card'; }
    public static function getLabel(): string { return 'Product Card'; }
    public static function getIcon(): string { return 'heroicon-o-cube'; }
    public static function getCategory(): string { return 'content'; }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')->label('Title')->required(),
            RichEditor::make('description')->label('Description'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'title' => '',
            'description' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        return $data['title'] ?: '(empty product card)';
    }
}
```

The Blade view at `resources/views/components/layup/product-card.blade.php`:

```blade
@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <h3>{{ $data['title'] ?? '' }}</h3>
    <div class="prose">{!! $data['description'] ?? '' !!}</div>
</div>
```

**Key rules:**
- `getType()` must be kebab-case and match the Blade view filename
- Every key in `getDefaultData()` must match a field name in `getContentFormSchema()`
- Blade views must include all four integration points: `id`, visibility classes, inline styles, animation attributes
- Design (colors, spacing) and Advanced (id, classes, CSS, animations) tabs are inherited -- do not re-declare them

The form schema automatically inherits Design (spacing, background) and Advanced (id, class, inline CSS) tabs from `BaseWidget`. You only define the Content tab.

### Form Field Packs

Common field patterns are available as reusable packs:

```php
use Crumbls\Layup\Support\FieldPacks;

public static function getContentFormSchema(): array
{
    return [
        TextInput::make('heading')->required(),
        ...FieldPacks::image('hero_image'),        // FileUpload + alt TextInput
        ...FieldPacks::link('cta'),                 // url TextInput + new_tab Toggle
        ...FieldPacks::colorPair('text', 'bg'),     // two ColorPicker fields
        ...FieldPacks::hoverColors('btn'),           // bg, hover_bg, text, hover_text colors
    ];
}
```

### Transforming Data Before Render

Override `prepareForRender()` to transform stored data before it reaches the Blade view:

```php
class CountdownWidget extends BaseWidget
{
    public static function prepareForRender(array $data): array
    {
        $data['target_timestamp'] = strtotime($data['target_date'] ?? 'now');
        $data['is_expired'] = $data['target_timestamp'] < time();

        return $data;
    }
}
```

This is called automatically in the render pipeline. The default implementation is a passthrough.

### Widget Validation Rules

Widgets can self-declare validation rules via `getValidationRules()`. The `ContentValidator` will query these before falling back to hardcoded rules:

```php
class ButtonWidget extends BaseWidget
{
    public static function getValidationRules(): array
    {
        return [
            'label' => 'required|string',
            'url' => 'required|string',
        ];
    }
}
```

### Widget Search Tags

Add extra terms so users can find your widget in the picker:

```php
public static function getSearchTerms(): array
{
    return ['cta', 'action', 'conversion', 'signup'];
}
```

### Deprecating Widgets

Mark widgets as deprecated to provide a transition period:

```php
public static function isDeprecated(): bool { return true; }

public static function getDeprecationMessage(): string
{
    return 'Use GalleryWidget instead. Removal planned for v2.0.';
}
```

Deprecated widgets are flagged in `layup:doctor` and `layup:audit` output.

### Widget Asset Declaration

Widgets can declare external JS/CSS dependencies:

```php
public static function getAssets(): array
{
    return [
        'js' => ['https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js'],
    ];
}
```

Collect assets from a content structure with `WidgetAssetCollector`:

```php
use Crumbls\Layup\Support\WidgetAssetCollector;

$assets = WidgetAssetCollector::fromContent($page->content);
// $assets = ['js' => [...], 'css' => [...]]
```

### onDuplicate Hook

Handle resource cloning when a widget is duplicated:

```php
public static function onDuplicate(array $data, ?WidgetContext $context = null): array
{
    if (! empty($data['src'])) {
        $newPath = 'layup/images/' . Str::uuid() . '.' . pathinfo($data['src'], PATHINFO_EXTENSION);
        Storage::disk(config('layup.uploads.disk', 'public'))->copy($data['src'], $newPath);
        $data['src'] = $newPath;
    }

    return $data;
}
```

### Registration

There are three ways to register custom widgets. Whichever you choose, the widget must be registered for **both** the Filament admin panel and the frontend renderer.

#### Option 1: Auto-Discovery (recommended)

Place widget classes in `app/Layup/Widgets/` and they will be auto-discovered on frontend routes automatically. However, **you must also register them with the Filament plugin** for the admin panel editor to recognize them:

```php
// app/Providers/Filament/AdminPanelProvider.php
use App\Layup\Widgets\MyWidget;

LayupPlugin::make()
    ->widgets([
        MyWidget::class,
    ])
```

Without this step, the widget will render correctly on the frontend but the admin edit form will appear empty (no form fields, just a save button) because the admin panel's WidgetRegistry doesn't know about the widget type.

The auto-discovery namespace and directory are configurable:

```php
// config/layup.php
'widget_discovery' => [
    'namespace' => 'App\\Layup\\Widgets',
    'directory' => null, // defaults to app_path('Layup/Widgets')
],
```

#### Option 2: Config file only

Add the widget class to the config. This registers it for both admin and frontend:

```php
// config/layup.php
'widgets' => [
    // ... built-in widgets ...
    \App\Layup\Widgets\MyWidget::class,
],
```

#### Option 3: Plugin only

Register via the plugin. This also covers both admin and frontend since the plugin populates the shared WidgetRegistry:

```php
LayupPlugin::make()
    ->widgets([MyWidget::class])
```

### Customizing the Widget Scaffold

To customize the templates used by `layup:make-widget`:

```bash
php artisan vendor:publish --tag=layup-stubs
```

This publishes `stubs/layup-widget.php.stub` and `stubs/layup-widget-view.blade.php.stub` to your project root, where you can modify them to match your team's conventions.

### Remove built-in widgets

```php
LayupPlugin::make()
    ->withoutWidgets([
        \Crumbls\Layup\View\HtmlWidget::class,
        \Crumbls\Layup\View\SpacerWidget::class,
    ])
```

## Testing

Layup ships testing helpers for verifying custom widgets and page content.

### Factory States

```php
use Crumbls\Layup\Models\Page;

// Page with specific widgets
$page = Page::factory()->withWidgets(['text', 'button'])->create();

// Page with explicit content structure
$page = Page::factory()->withContent([...])->create();
```

### Assertions

Add the `LayupAssertions` trait to your test case:

```php
use Crumbls\Layup\Testing\LayupAssertions;

test('homepage has expected widgets', function () {
    $page = Page::factory()->withWidgets(['heading', 'text', 'button'])->create();

    $this->assertPageContainsWidget($page, 'heading');
    $this->assertPageContainsWidget($page, 'button', expectedCount: 1);
    $this->assertPageDoesNotContainWidget($page, 'html');
    $this->assertPageRenders($page);
});

test('custom widget renders without errors', function () {
    $this->assertWidgetRenders('my-widget', ['title' => 'Hello']);
});
```

#### Widget Contract Assertions

Validate that your custom widget follows all conventions:

```php
test('widget satisfies the contract', function () {
    $this->assertWidgetContractValid(MyWidget::class);
});

test('defaults cover all form fields', function () {
    $this->assertDefaultsCoverFormFields(MyWidget::class);
});

test('renders with default data', function () {
    $this->assertWidgetRendersWithDefaults(MyWidget::class);
});
```

Generate these tests automatically with `php artisan layup:make-widget MyWidget --with-test`.

## Artisan Commands

| Command | Description |
|---------|-------------|
| `layup:make-controller {name}` | Scaffold a frontend controller extending AbstractController |
| `layup:make-widget {name}` | Scaffold a custom widget (PHP class + Blade view). Use `--with-test` to generate a Pest test. Remember to [register it](#registration) with the plugin. |
| `layup:debug-widget {type}` | Dump the full resolved state of a widget (class, fields, defaults, validation, assets, rendered HTML). Use `--data='{...}'` to pass custom data. |
| `layup:safelist` | Generate the Tailwind safelist file |
| `layup:audit` | Audit page content for structural issues |
| `layup:doctor` | Diagnose setup issues (plugin registration, storage symlink, layout, @layupScripts, safelist in Tailwind, widgets, config) |
| `layup:list-widgets` | List all registered widgets with type, label, category, and source |
| `layup:search {type}` | Find pages containing a widget type. Use `--unused` to find unregistered widgets |
| `layup:export` | Export pages as JSON files |
| `layup:import` | Import pages from JSON files |
| `layup:install` | Full guided setup (config, migrations, storage link, layout, Filament assets, safelist, doctor check) |

## Configuration Reference

```php
// config/layup.php
return [
    // Widget classes available in the page builder (registered for both admin and frontend)
    'widgets' => [ /* ... */ ],

    // Auto-discover widgets from this namespace/directory (frontend only --
    // for admin panel access, also register via LayupPlugin::make()->widgets([...]))
    'widget_discovery' => [
        'namespace' => 'App\\Layup\\Widgets',
        'directory' => null, // defaults to app_path('Layup/Widgets')
    ],

    // Filesystem disk for FileUpload fields in the page builder
    'uploads' => [
        'disk' => 'public',
    ],

    // Page model and table name (swap per dashboard)
    'pages' => [
        'table' => 'layup_pages',
        'model' => \Crumbls\Layup\Models\Page::class,
        'default_slug' => null, // Slug to serve at the index route (null = empty slug)
    ],

    // Automatically save content revisions when a page is updated
    'revisions' => [
        'enabled' => true,
        'max' => 50,
    ],

    // Frontend rendering
    'frontend' => [
        'enabled'         => true,
        'prefix'          => 'pages',        // set to '' to serve at site root
        'middleware'       => ['web'],
        'domain'          => null,
        'layout'          => 'app',
        'view'            => 'layup::frontend.page',
        'max_width'       => 'container',
        'include_scripts' => true,
        'excluded_paths'  => [],             // extra paths to exclude when prefix is ''
    ],

    // Tailwind safelist
    'safelist' => [
        'enabled'       => true,
        'auto_sync'     => true,
        'path'          => 'storage/layup-safelist.txt',
        'extra_classes' => [], // Additional classes to always include
    ],

    // Responsive breakpoints
    'breakpoints' => [
        'sm' => ['label' => 'sm', 'width' => 640,  'icon' => 'heroicon-o-device-phone-mobile'],
        'md' => ['label' => 'md', 'width' => 768,  'icon' => 'heroicon-o-device-tablet'],
        'lg' => ['label' => 'lg', 'width' => 1024, 'icon' => 'heroicon-o-computer-desktop'],
        'xl' => ['label' => 'xl', 'width' => 1280, 'icon' => 'heroicon-o-tv'],
    ],

    'default_breakpoint' => 'lg',

    // Row layout presets (column spans, must sum to 12)
    'row_templates' => [
        [12], [6, 6], [4, 4, 4], [3, 3, 3, 3],
        [8, 4], [4, 8], [3, 6, 3], [2, 8, 2],
    ],
];
```

## Publishing Assets

Layup supports publishing various asset groups for customization:

| Tag | Command | Description |
|-----|---------|-------------|
| `layup-config` | `php artisan vendor:publish --tag=layup-config` | Config file to `config/layup.php` |
| `layup-views` | `php artisan vendor:publish --tag=layup-views` | Blade views to `resources/views/vendor/layup/` |
| `layup-routes` | `php artisan vendor:publish --tag=layup-routes` | Route file to `routes/layup.php` |
| `layup-scripts` | `php artisan vendor:publish --tag=layup-scripts` | Alpine.js components to `resources/js/vendor/layup.js` |
| `layup-templates` | `php artisan vendor:publish --tag=layup-templates` | Page templates to `resources/layup/templates/` |
| `layup-translations` | `php artisan vendor:publish --tag=layup-translations` | Language files to `lang/vendor/layup/` |
| `layup-stubs` | `php artisan vendor:publish --tag=layup-stubs` | Widget scaffolding stubs to `stubs/` |

Publishing views is useful when you need to customize frontend rendering (page layout, row/column markup, or individual widget templates). After publishing, edit the files in `resources/views/vendor/layup/` -- Laravel will use your copies instead of the package defaults.

## Multiple Dashboards

To use Layup across multiple Filament panels with separate page tables, override the model in your published config:

```php
// config/layup.php
'pages' => [
    'table' => 'layup_pages',
    'model' => \App\Models\PageB::class,
],
```

Your custom model extends the base Page:

```php
namespace App\Models;

class PageB extends \Crumbls\Layup\Models\Page
{
    protected $table = 'custom_pages';
}
```

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for development setup, code style, and testing guidelines. For widget development, see [agents.md](agents.md) for a detailed reference covering every method, Blade pattern, and common pitfall.

## Vision & Roadmap

See [VISION.md](VISION.md) for where Layup is headed and how you can help shape it.

## License

MIT — see [LICENSE.md](LICENSE.md)
