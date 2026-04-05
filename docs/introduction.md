---
title: Introduction
weight: 1
---

Layup is a visual page builder plugin for [Filament 5](https://filamentphp.com/). It brings Divi-style drag-and-drop editing to your admin panel with a 12-column responsive grid, live breakpoint previews, and undo/redo.

## Who is it for?

Layup is built for Laravel developers who use Filament and need to give content editors a visual page builder without leaving the admin panel. Editors get a drag-and-drop interface. Developers keep full control over widgets, theming, and rendering.

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

Layup stores page content as a JSON structure of rows, columns, and widgets. Each widget holds its own data (content, design settings, advanced options). The builder interface lives inside a custom Filament form field (`LayupBuilder`) that renders an Alpine.js-powered canvas.

On the frontend, the JSON content is walked recursively to build a tree of Row, Column, and Widget view components, each rendered through Blade templates. The grid uses flexbox with Tailwind utility classes for responsive column widths.
