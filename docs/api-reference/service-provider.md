---
title: Service Provider
weight: 3
nav_title: Service Provider
order: 30
---

## LayupServiceProvider

`Crumbls\Layup\LayupServiceProvider`

The service provider is auto-discovered by Laravel. It handles all package bootstrapping.

### Registered singletons

- `WidgetRegistry` -- the central registry of all available widgets
- `LayupTheme` -- theme configuration (colors, fonts, border radius)

### Published assets

| Tag | Description |
|-----|-------------|
| `layup-config` | `config/layup.php` |
| `layup-views` | Blade views to `resources/views/vendor/layup` |
| `layup-scripts` | JavaScript to `resources/js/vendor/layup.js` |
| `layup-routes` | Route file to `routes/layup.php` |
| `layup-templates` | Page templates to `resources/layup/templates` |
| `layup-translations` | Language files to `lang/vendor/layup` |
| `layup-stubs` | Widget scaffolding stubs to `stubs/` |

### Artisan commands

| Command | Description |
|---------|-------------|
| `layup:install` | Guided setup wizard |
| `layup:safelist` | Generate Tailwind safelist file |
| `layup:make-widget {name}` | Scaffold a new widget class + Blade view |
| `layup:make-controller {name}` | Scaffold a custom page controller |
| `layup:list-widgets` | List all registered widgets |
| `layup:doctor` | Health check for installation |
| `layup:debug-widget {type}` | Inspect a widget's metadata and form schema |
| `layup:audit` | Audit content across all pages |
| `layup:export` | Export a page to JSON |
| `layup:import` | Import a page from JSON |
| `layup:search` | Search across page content |
| `layup:publish-scheduled` | Promote scheduled pages whose publish time has arrived |

### Blade directives

```blade
{{-- Include Alpine.js animation scripts --}}
@layupScripts

{{-- Render a content array --}}
@layup($page->content)
```

### Blade component

```blade
{{-- Render a single widget --}}
<x-layup-widget :type="$type" :data="$data" />
```

## LayupPlugin

`Crumbls\Layup\LayupPlugin`

The Filament plugin. Register in your panel provider:

```php
use Crumbls\Layup\LayupPlugin;

LayupPlugin::make()
    ->colors(['primary' => '#3b82f6', 'accent' => '#8b5cf6'])
    ->darkColors(['primary' => '#60a5fa'])
    ->fonts(['heading' => 'Playfair Display, serif', 'body' => 'Inter, sans-serif'])
    ->borderRadius('0.5rem')
    ->withoutPanelColors()           // Don't inherit Filament panel colors
    ->widgets([MyWidget::class])     // Add extra widgets
    ->withoutWidgets([LoginWidget::class])  // Remove widgets
    ->withoutConfigWidgets()         // Skip config widgets, use only those passed via widgets()
```

### Theme inheritance

By default, `LayupPlugin` reads colors from your Filament panel's `->colors()` config and uses them as theme defaults. Call `->withoutPanelColors()` to disable this.

Theme colors set via `->colors()` override inherited panel colors. Dark mode colors not explicitly set via `->darkColors()` are auto-lightened from the light mode values.
