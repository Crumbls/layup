---
title: Theme System
weight: 7
nav_title: Theme System
order: 70
---

Layup includes a global theme that outputs CSS custom properties and utility classes. Widgets use these variables for colors, fonts, and border radius so pages stay visually consistent.

## How it works

The `@layupScripts` directive outputs a `<style>` block with `:root` custom properties and matching utility classes:

```css
:root {
    --layup-primary: #3b82f6;
    --layup-on-primary: #ffffff;
    --layup-secondary: #6b7280;
    --layup-accent: #f59e0b;
    --layup-success: #22c55e;
    --layup-warning: #f59e0b;
    --layup-danger: #ef4444;
    --layup-surface: #ffffff;
    --layup-on-surface: #111827;
    --layup-border: #e5e7eb;
    --layup-muted: #6b7280;
    --layup-font-heading: Arial;
    --layup-radius: 0.5rem;
}

.layup-bg-primary { background-color: var(--layup-primary); }
.layup-text-primary { color: var(--layup-primary); }
.layup-border-primary { border-color: var(--layup-primary); }
.layup-hover-bg-primary:hover { background-color: var(--layup-primary); }
.layup-hover-text-primary:hover { color: var(--layup-primary); }
```

## Configuring colors

By default, Layup inherits colors from your Filament panel's `->colors()` configuration. To override or extend:

```php
use Crumbls\Layup\LayupPlugin;

LayupPlugin::make()
    ->colors([
        'primary' => '#e11d48',
        'secondary' => '#6366f1',
        'accent' => '#f59e0b',
    ])
```

Values merge with (and override) inherited panel colors.

To stop inheriting panel colors entirely:

```php
LayupPlugin::make()
    ->withoutPanelColors()
    ->colors([
        'primary' => '#3b82f6',
    ])
```

## Dark mode

Layup auto-generates lightened color variants for dark mode. No extra configuration needed -- the CSS includes both `:root {}` and `.dark {}` blocks.

Override specific dark mode colors:

```php
LayupPlugin::make()
    ->colors(['primary' => '#e11d48'])
    ->darkColors(['primary' => '#fb7185'])
```

Colors not specified in `darkColors()` use the auto-lightened variant. The auto-lightening converts to HSL, boosts lightness by 25% (capped at 85%), and converts back.

```css
:root { --layup-primary: #e11d48; }
.dark { --layup-primary: #fb7185; }  /* manual override */
```

## Fonts

```php
LayupPlugin::make()
    ->fonts([
        'heading' => 'Playfair Display, serif',
        'body' => 'Inter, sans-serif',
    ])
```

Generates `--layup-font-heading`, `--layup-font-body` custom properties and `.layup-font-heading`, `.layup-font-body` utility classes.

## Border radius

```php
LayupPlugin::make()
    ->borderRadius('0.5rem')
```

Generates `--layup-radius` and `.layup-rounded`.

## Utility class reference

| Class Pattern | CSS |
|---------------|-----|
| `layup-bg-{name}` | `background-color: var(--layup-{name})` |
| `layup-text-{name}` | `color: var(--layup-{name})` |
| `layup-border-{name}` | `border-color: var(--layup-{name})` |
| `layup-hover-bg-{name}:hover` | `background-color: var(--layup-{name})` |
| `layup-hover-text-{name}:hover` | `color: var(--layup-{name})` |
| `layup-font-{name}` | `font-family: var(--layup-font-{name})` |
| `layup-rounded` | `border-radius: var(--layup-radius)` |

## Using theme variables in custom widgets

```blade
{{-- Inline style --}}
<div style="background-color: var(--layup-primary)">...</div>

{{-- Utility class --}}
<button class="layup-bg-primary layup-hover-bg-accent text-white">Click</button>
```

## ColorPicker field

Layup provides a `ColorPicker` form field that shows theme-sourced swatches plus an optional custom color input:

```php
use Crumbls\Layup\Forms\Components\ColorPicker;

ColorPicker::make('bg_color')
    ->label('Background')
```

Override swatches:

```php
ColorPicker::make('bg_color')
    ->swatches(['Red' => '#ef4444', 'Blue' => '#3b82f6'])
```

Disable the custom color input:

```php
ColorPicker::make('bg_color')
    ->allowCustom(false)
```

All built-in widgets use `ColorPicker` for color fields in their Design tabs.
