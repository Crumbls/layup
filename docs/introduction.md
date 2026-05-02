---
title: Introduction
weight: 1
---

Layup is a Filament form field that turns any JSON column into a visual content editor. The field is the product. The bundled Pages resource is one application of it.

```php
use Crumbls\Layup\Forms\Components\LayupBuilder;

public function form(Schema $schema): Schema
{
    return $schema->components([
        LayupBuilder::make('content')->columnSpanFull(),
    ]);
}
```

Drop that into any Filament resource form and editors get a Divi-style canvas with rows, columns, breakpoint previews, undo/redo, and 96 extensible widgets. Pair it with the `HasLayupContent` trait on the Eloquent model to render the saved content on the frontend.

## Two ways to use Layup

**Use the field on your own model.** Add a JSON column, drop `LayupBuilder::make()` into your existing Filament form, render with `@layup($model->content)`. No Pages resource, no opinions about routing or URLs. See [Embedding the field](embedding-the-field.md) and [Field-only installation](field-only-installation.md).

**Use the bundled Pages resource.** Layup ships with a complete page CMS -- nested pages, scheduled publishing, SEO meta, frontend routes -- so you can install once and start editing. See [Getting started](getting-started/_index.md).

## Who is it for?

Laravel developers who use Filament and want a visual content editor either as a primitive (a field) or as a turnkey CMS. Editors get a drag-and-drop interface; developers keep full control over widgets, theming, and rendering.

## Key features

- **95+ built-in widgets** across five categories: Content, Media, Interactive, Layout, and Advanced
- **12-column responsive grid** with per-breakpoint column widths (sm, md, lg, xl)
- **Three-tab widget forms** -- every widget gets Content, Design, and Advanced tabs out of the box
- **Breakpoint preview** -- preview your page at different screen sizes directly in the builder
- **Theme system** -- set colors, fonts, and border radius via the plugin config; inherits your Filament panel colors by default
- **Dark mode** -- auto-generated dark mode CSS variables with optional manual overrides
- **Entrance animations** -- fade, slide, and zoom animations powered by Alpine.js and Intersection Observer
- **Responsive visibility** -- hide any widget, row, or column at specific breakpoints
- **Revision history** -- automatic content versioning with configurable max revisions and one-click restore
- **Page templates** -- built-in templates (Blank, Landing, About, Contact, Pricing) plus custom template support
- **Tailwind safelist generation** -- auto-generates a safelist file so Tailwind picks up dynamic classes
- **Content validation** -- structural and widget-level validation on every save
- **Frontend rendering** -- optional routes that serve published pages with SEO meta tags and JSON-LD structured data
- **Custom widgets** -- scaffold new widgets with `php artisan layup:make-widget` and auto-discovery
- **Import/Export** -- JSON-based page import and export via Artisan commands
- **`HasLayupContent` trait** -- add Layup content rendering to any Eloquent model

## How it works

The `LayupBuilder` field renders an Alpine.js-powered canvas inside the Filament form. As editors arrange rows, columns, and widgets, the field serializes the layout to a JSON structure that Laravel persists to your column (any JSON-cast column on any model).

On the frontend, that JSON is walked recursively to build a tree of Row, Column, and Widget view components, each rendered through Blade templates. The grid uses flexbox with Tailwind utility classes for responsive column widths. Render anywhere with the `@layup` Blade directive, the `HasLayupContent` trait's `toHtml()` method, or the `<x-layup-widget>` component for individual widgets.
