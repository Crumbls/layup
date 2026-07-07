---
title: SEO Meta
weight: 7
nav_title: SEO Meta
order: 70
---

Layup emits standard meta tags (Open Graph, Twitter Cards, canonical, JSON-LD) on every published page. This page covers the integration point, what's emitted, and the per-page and site-wide knobs you can tune.

## Philosophy

Layup is a page builder, not an SEO tool. We emit well-formed meta tags from data your editors already provide — title, description, featured image, parent chain, publish date. We deliberately do not ship keyword analysis, content scoring, SERP previews, or any of the things a dedicated SEO tool does well. If you need that, reach for one of those tools.

## The integration point: `<x-layup-seo />`

Drop one line into your layout's `<head>`:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>

    {{-- Your site-wide defaults go here. --}}
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:image" content="{{ asset('default-og.png') }}">

    {{-- Layup adds page-specific overrides on layup-rendered routes
         and emits nothing on every other route. --}}
    <x-layup-seo />
</head>
<body>
    {{ $slot }}
</body>
</html>
```

That's it. The component:

- **Resolves the page automatically.** When a request goes through Layup's controller (the bundled one or your own subclass of `AbstractController`), the page is shared into view scope as `layupPage`. The component picks it up.
- **No-ops on non-layup routes.** Outside a Layup request, the component renders nothing. Safe to leave in a shared layout used by both Layup pages and other parts of your app.
- **Doesn't stomp your defaults.** Layup's tags emit *after* whatever you put above them. Where they overlap (e.g., `og:image`), the page-specific value takes precedence — and on non-layup pages, your site-wide defaults are the only ones that render.

## What gets emitted

For every published page, the component emits:

| Tag | Source |
|-----|--------|
| `<meta name="description">` | `meta.description` (omitted when empty) |
| `<meta property="og:title">` | `Page::getMetaTitle()` (page title + optional site suffix) |
| `<meta property="og:description">` | `meta.description` (omitted when empty) |
| `<meta property="og:type">` | `article` when `published_at` is set, else `website` |
| `<meta property="og:url">` | `Page::getUrl()` |
| `<meta property="og:locale">` | `app()->getLocale()` |
| `<meta property="og:site_name">` | `layup.seo.site_name` (or `app.name`) |
| `<meta property="og:image">` | featured image, or `layup.seo.default_og_image` |
| `<meta name="twitter:card">` | `summary_large_image` when an image is present, else `summary` |
| `<meta name="twitter:title">` | `getMetaTitle()` |
| `<meta name="twitter:description">` | `meta.description` (omitted when empty) |
| `<meta name="twitter:image">` | featured image |
| `<meta property="article:published_time">` | `published_at` |
| `<meta property="article:modified_time">` | `updated_at` |
| `<link rel="canonical">` | `Page::getUrl()` |
| `<meta name="robots">` | `noindex,nofollow` when `meta.noindex` is true |
| JSON-LD | `WebPage` / `Article` / `FAQPage` plus `BreadcrumbList` |

## Per-page settings

Editors set the following from the **Page Settings** modal:

- **Meta Description** — the main `description` value. Reused by OG and Twitter.
- **Featured Image** — the OG / Twitter card image.
- **Hide from search engines** — adds `noindex,nofollow`. Useful for unlisted landing pages or anything you don't want crawled.
- **Publish at** — surfaces as `article:published_time`.

The page record's `updated_at` becomes `article:modified_time` automatically.

## Site-wide configuration

```php
// config/layup.php

'seo' => [
    // Appended to every page's <title>, e.g. ' – Site Name'.
    'title_suffix' => null,

    // og:site_name fallback. Falls through to config('app.name') when null.
    'site_name' => null,

    // Path or URL used when a page has no featured image.
    // Path is resolved through layup.uploads.disk; absolute URLs pass through.
    'default_og_image' => null,

    // Label for the root crumb in BreadcrumbList JSON-LD.
    'home_breadcrumb_label' => 'Home',
],
```

## Custom controllers

If your controller extends `Crumbls\Layup\Http\Controllers\AbstractController`, the page is shared into view scope automatically and `<x-layup-seo />` works with no further wiring.

If you have a fully custom controller that doesn't extend `AbstractController`, pass the page in explicitly:

```blade
<x-layup-seo :page="$myPage" />
```

You can also use this form to render meta for a different page than the one currently being rendered (e.g., previews, embeds).

## Disabling Layup's frontend

`config('layup.frontend.enabled') = false` only disables Layup's auto-registered routes. Custom controllers extending `AbstractController` still render Layup pages and still share `layupPage` into view scope. If you want to bypass Layup's controller entirely, route to your own controller and don't extend `AbstractController`.

## Structured data

`Page::getStructuredData()` returns the JSON-LD documents the component emits. The first is the page schema, controlled by `meta.schema_type`:

- `WebPage` (default)
- `Article` / `BlogPosting` — picks up `meta.author`
- `FAQPage` — auto-detects accordion / toggle widgets and emits `Question` / `Answer` entries

The second document is `BreadcrumbList`. When the page has a parent chain (`parent_id`), the breadcrumbs walk that chain and use real page titles. For legacy pages whose slug contains slashes but has no `parent_id`, breadcrumbs fall back to splitting the URL path with title-cased segments — the leaf crumb still uses the page's actual title.

## Customizing what gets emitted

If you need different tags, the simplest path is to skip the component and call the model methods directly in your own meta block:

```blade
@isset($layupPage)
    <meta name="description" content="{{ $layupPage->getMetaDescription() }}">
    <meta property="og:title" content="{{ $layupPage->getMetaTitle() }}">
    {{-- ...your own subset, in whatever order you like... --}}
@endisset
```

`$layupPage` is the same view-shared variable the component uses internally, so this works in any layout that's rendered through `AbstractController`.

## Sitemap

Layup does not generate `sitemap.xml` for you, but `Page::sitemapEntries()` returns the URL / `lastmod` / priority data you need to build one with the package of your choice (e.g. `spatie/laravel-sitemap`).
