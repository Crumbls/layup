---
title: Customization
weight: 7
nav_title: Customization
order: 70
---

Layup is built to be extended. The defaults work out of the box, but most non-trivial use cases will hit one or more of these customization points. This page is a map: pick what you want to change and jump to the guide.

## I want to...

| Goal | Guide | Config or API |
|---|---|---|
| Embed the field on my own model | [Embedding the field](../embedding-the-field.md) | `LayupBuilder::make('content')` |
| Disable the bundled Pages resource entirely | [Disable the Pages resource](disable-pages-resource.md) | `pages.enabled = false` |
| Use a custom Page model (multi-dashboard, custom table) | [Swapping the Page model](swapping-the-page-model.md) | `pages.model` |
| Add or remove widgets in the picker | [Filament plugin API](filament-plugin-api.md) and [Extending widgets](extending-widgets.md) | `->widgets()`, `->withoutWidgets()` |
| Build my own widget from scratch | [Custom widgets](custom-widgets.md) | `php artisan layup:make-widget` |
| Make a widget render through Livewire | [Livewire-rendered widgets](livewire-widgets.md) | `BaseLivewireWidget` |
| Override the page render template / layout | [Frontend rendering](frontend-rendering.md) | `frontend.layout`, `frontend.view` |
| Add a custom page template | [Page templates](page-templates.md) | `page_layout.templates` |
| Change colors, fonts, or border radius | [Theme system](theme-system.md) and [Filament plugin API](filament-plugin-api.md) | `->colors()`, `->fonts()`, `->borderRadius()` |
| Customize SEO meta tags / structured data | [SEO and meta tags](seo-meta.md) | `seo.*` config |
| Hook the Tailwind safelist into my build | [Tailwind safelist](tailwind-safelist.md) | `safelist.*` config, `SafelistChanged` event |
| Add lifecycle hooks to a widget | [Custom widgets](custom-widgets.md) | `onCreate`, `onSave`, `onDelete`, `onDuplicate` |
| Render Layup content outside the built-in routes | [Rendering content](rendering-content.md) | `@layup`, `HasLayupContent`, `<x-layup-widget>` |
| Test my custom widgets | [Testing](testing.md) | `LayupAssertions` trait |

## Two starting points

Most customization questions reduce to one of two scenarios:

**You want the field, not the CMS.** You have your own models, your own routing, your own front-end. Read [Embedding the field](../embedding-the-field.md) and [Disable the Pages resource](disable-pages-resource.md). Skip the Pages-related guides.

**You want to extend the bundled CMS.** You like the Pages resource but want different widgets, theme, or rendering. Start with [Filament plugin API](filament-plugin-api.md), then move on to [Frontend rendering](frontend-rendering.md), [Page templates](page-templates.md), and [Theme system](theme-system.md).
