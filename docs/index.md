---
title: Introduction
nav_title: Introduction
weight: 10
order: 10
---

# Introduction

Layup is a visual page-builder field for Filament. It turns a JSON column into an editor canvas with sections, rows, responsive columns, and registered widgets.

The bundled Pages resource is optional. You can use Layup as a complete page CMS, or you can use only `Crumbls\Layup\Forms\Components\LayupBuilder` on your own Eloquent model and render the saved content yourself.

## What it does

- Provides a flex-based 12-column grid with `sm`, `md`, `lg`, and `xl` breakpoints.
- Registers 96 built-in widget classes from `config/layup.php`.
- Ships optional frontend routes for published pages.
- Includes SEO helpers, structured data, breadcrumbs, page revisions, scheduled publishing, and Tailwind safelist generation.
- Supports custom widgets, custom page models, custom rendering, and plugin-level widget/theme configuration.

## When to use it

Use Layup when a Laravel application needs editor-managed landing pages, marketing pages, documentation-style pages, or structured content blocks inside an existing resource.

Choose the bundled Pages resource when you want Layup to own page records, routing, status, SEO metadata, revisions, and scheduled publishing.

Choose field-only mode when your application already has its own model, resource, route, authorization, or publishing workflow and only needs the visual builder field.

## Requirements

| Package | Version |
| --- | --- |
| PHP | `^8.3` |
| Laravel framework | `^12.0 || ^13.0` |
| Filament | `^5.0` |

`livewire/livewire` is suggested when you build widgets that extend `Crumbls\Layup\View\BaseLivewireWidget`. Standard Blade-rendered widgets do not require declaring Livewire as a separate application dependency beyond what Filament already brings to the app.

## Core concepts

| Concept | Source class or config | Purpose |
| --- | --- | --- |
| Builder field | `Crumbls\Layup\Forms\Components\LayupBuilder` | Stores Layup content in a JSON column. |
| Page model | `Crumbls\Layup\Models\Page` | Optional CMS model with status, nesting, SEO, revisions, and scheduling. |
| Widget registry | `Crumbls\Layup\Support\WidgetRegistry` | Maps widget type strings to widget classes. |
| Widget classes | `Crumbls\Layup\View\BaseBladeWidget`, `BaseLivewireWidget` | Define editor fields, defaults, preview text, and frontend rendering. |
| Frontend rendering | `@layup`, `HasLayupContent`, `<x-layup-widget>` | Renders saved content as HTML. |
| Safelist | `php artisan layup:safelist` | Writes dynamic Tailwind classes used by widgets and saved content. |

## Quick start

```bash
composer require crumbls/layup
php artisan layup:install
```

Register the plugin in a Filament panel provider:

```php
use Crumbls\Layup\LayupPlugin;

public function panel(\Filament\Panel $panel): \Filament\Panel
{
    return $panel
        ->plugins([
            LayupPlugin::make(),
        ]);
}
```

For the full install path, see [Installation](installation.md). For field-only usage, see [Field-only installation](field-only-installation.md) and [Embedding the field](embedding-the-field.md).
