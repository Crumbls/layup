---
title: Filament Plugin API
weight: 1
---

`LayupPlugin` is the main entry point for customizing Layup at the Filament panel level. Every method on the plugin is fluent and returns `$this`, so you can chain them in your panel provider.

```php
use Crumbls\Layup\LayupPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            LayupPlugin::make()
                ->widgets([\App\Layup\Widgets\HeroBlock::class])
                ->withoutWidgets([\Crumbls\Layup\View\EmbedWidget::class])
                ->colors(['primary' => '#3b82f6'])
                ->fonts(['heading' => 'Playfair Display, serif'])
                ->borderRadius('0.5rem'),
        ]);
}
```

Use one plugin instance per panel. Different panels can have completely different widget sets, themes, and configurations.

## Widget management

### `widgets(array $widgets): static`

Register additional widget classes alongside the ones loaded from `config/layup.php`.

```php
LayupPlugin::make()->widgets([
    \App\Layup\Widgets\HeroBlock::class,
    \App\Layup\Widgets\TestimonialCarousel::class,
])
```

Combine with auto-discovery (set `widget_discovery.namespace` in config) when you want the package to scan a directory for you. See [Custom widgets](custom-widgets.md).

### `withoutWidgets(array $widgets): static`

Remove widget types from the picker. Pass the widget class name -- the plugin resolves the type and unregisters it.

```php
LayupPlugin::make()->withoutWidgets([
    \Crumbls\Layup\View\EmbedWidget::class,
    \Crumbls\Layup\View\CodeWidget::class,
])
```

Useful for locking down the editor to a curated subset of widgets, or removing widgets that conflict with your security policy (e.g., raw HTML).

### `withoutConfigWidgets(): static`

Skip loading widgets from `config/layup.php` entirely. Combine with `widgets()` to ship a strictly-curated widget set.

```php
LayupPlugin::make()
    ->withoutConfigWidgets()
    ->widgets([
        \App\Layup\Widgets\Heading::class,
        \App\Layup\Widgets\Paragraph::class,
        \App\Layup\Widgets\Image::class,
    ])
```

This is the cleanest way to ship a "minimal" editor with only your own widgets.

## Theme

### `colors(array $colors): static`

Set the theme colors used by built-in widgets and surfaced as CSS custom properties (`--layup-color-primary`, etc.). Merges with any colors inherited from the Filament panel.

```php
LayupPlugin::make()->colors([
    'primary' => '#3b82f6',
    'secondary' => '#10b981',
    'accent' => '#f59e0b',
])
```

### `darkColors(array $colors): static`

Override dark mode colors. Any color you do not specify here is auto-lightened from its light-mode counterpart.

```php
LayupPlugin::make()
    ->colors(['primary' => '#1e40af'])
    ->darkColors(['primary' => '#60a5fa'])
```

### `fonts(array $fonts): static`

Set font families for the theme. Standard keys are `heading` and `body`, but you can use any key your widgets reference.

```php
LayupPlugin::make()->fonts([
    'heading' => 'Playfair Display, serif',
    'body' => 'Inter, sans-serif',
])
```

### `borderRadius(string $radius): static`

Set the global default border radius. Accepts any valid CSS length (`'0'`, `'0.5rem'`, `'8px'`).

```php
LayupPlugin::make()->borderRadius('0.5rem')
```

### `withoutPanelColors(): static`

By default, Layup inherits the Filament panel's color palette so the editor matches your admin UI. Call this method to disable that inheritance and use only the colors you set explicitly via `colors()`.

```php
LayupPlugin::make()
    ->withoutPanelColors()
    ->colors(['primary' => '#000000'])
```

For the full theme system (CSS custom properties, dark mode generation, the `LayupTheme` singleton), see [Theme system](theme-system.md).

## Multiple panels

Each Filament panel gets its own `LayupPlugin` instance. You can configure them independently:

```php
// AdminPanelProvider.php
LayupPlugin::make()
    ->colors(['primary' => '#1e40af'])
    ->widgets([\App\Layup\Widgets\AdminOnly::class])

// MarketingPanelProvider.php
LayupPlugin::make()
    ->colors(['primary' => '#10b981'])
    ->withoutWidgets([\Crumbls\Layup\View\CodeWidget::class])
```

If you also need separate page tables per panel, see [Swapping the Page model](swapping-the-page-model.md).
