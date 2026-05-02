---
title: Rendering Content
weight: 9
---

There are several ways to render Layup content outside the built-in frontend routes.

## The `@layup` directive

Render any content array in a Blade template:

```blade
@layup($page->content)
```

This is the simplest approach. It instantiates `LayupContent`, walks the content tree, and renders all rows, columns, and widgets.

## The `LayupContent` class

Use the helper class directly. It implements `Htmlable`:

```php
use Crumbls\Layup\Support\LayupContent;

{{ new LayupContent($model->field) }}
```

## The `<x-layup-widget>` component

Render a single widget outside the page builder:

```blade
<x-layup-widget type="button" :data="['label' => 'Sign Up', 'url' => '/register']" />
<x-layup-widget type="testimonial" :data="$testimonialData" />
```

This resolves the widget from the registry, applies Design/Advanced tab defaults, and renders the widget's Blade view. Unknown types render nothing and log a warning.

## The `HasLayupContent` trait

Add Layup rendering to any Eloquent model with a JSON content column:

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

Then render in Blade:

```blade
{!! $post->toHtml() !!}
```

The trait provides:

| Method | Returns | Description |
|--------|---------|-------------|
| `toHtml()` | `string` | Render content to HTML |
| `getSectionTree()` | `array` | Sections with hydrated Row/Column/Widget objects |
| `getContentTree()` | `array` | Flat list of Row objects |
| `getUsedClasses()` | `array` | Tailwind classes used in content |
| `getUsedInlineStyles()` | `array` | Inline styles used in content |

## WidgetData in Blade views

Use the `WidgetData` value object for typed, null-safe access in widget Blade templates:

```blade
@php $d = \Crumbls\Layup\Support\WidgetData::from($data); @endphp
<h1>{{ $d->string('heading') }}</h1>
<img src="{{ $d->storageUrl('background_image') }}" alt="{{ $d->string('alt') }}" />
@if($d->bool('show_overlay'))
    <div style="opacity: {{ $d->float('overlay_opacity', 0.5) }}"></div>
@endif
```

Available methods: `string()`, `bool()`, `int()`, `float()`, `array()`, `has()`, `storageUrl()`, `url()`, `toArray()`. Implements `ArrayAccess` for backward compatibility.

## Multiple dashboards

To use Layup across multiple Filament panels with separate page tables, swap the Page model. See [Swapping the Page model](swapping-the-page-model.md) for the full walkthrough.

## Render isolation

Broken widgets do not crash entire pages. If a widget throws an exception during rendering, it is caught and logged. In debug mode, an HTML comment shows the error. In production, the widget is silently skipped.
