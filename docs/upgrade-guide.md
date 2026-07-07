---
title: Upgrade guide
nav_title: Upgrade guide
weight: 90
order: 130
---

# Upgrade guide

This guide covers upgrade-sensitive behavior exposed by the current source. For release-by-release notes, also read the repository [CHANGELOG](https://github.com/Crumbls/layup/blob/main/CHANGELOG.md).

## Check platform requirements

The current package supports PHP `^8.3`, Laravel `^12.0 || ^13.0`, and Filament `^5.0`.

```bash
composer why-not crumbls/layup:^1
composer update crumbls/layup --with-dependencies
```

## Publish and run migrations

Layup ships migrations for pages, page revisions, nesting, authors, scheduled publishing, and featured images.

```bash
php artisan migrate
```

The scheduled publishing migration backfills `published_at` for existing published rows. The nesting migration backfills `path` from `slug` for existing pages.

## Review config changes

Republish the config to a temporary branch or review the published diff before merging it into your application:

```bash
php artisan vendor:publish --tag=layup-config --force
```

Review `pages.model`, `pages.table`, `frontend.prefix`, `frontend.excluded_paths`, `scheduling.auto_publish`, `safelist.path`, `safelist.auto_sync`, and `page_layout.templates`.

## Regenerate the safelist

```bash
php artisan layup:safelist
```

Then rebuild your frontend assets.

## Audit pages and widgets

```bash
php artisan layup:doctor
php artisan layup:audit
php artisan layup:list-widgets
php artisan layup:search --unused
```

## Livewire-rendered widgets

Blade-rendered widgets remain the default path. If your application has custom widgets extending `Crumbls\Layup\View\BaseLivewireWidget`, make sure the referenced Livewire components still exist and accept a `data` prop. See [Livewire-rendered widgets](customization/livewire-widgets.md).

## Custom page models

Custom page models should continue extending `Crumbls\Layup\Models\Page` unless the application only uses Layup as a field. The base model owns path generation, published scopes, revisions, SEO helpers, and scheduled publishing behavior.

If your content model has a different workflow, use field-only mode instead of subclassing the Page model. See [Embedding the field](embedding-the-field.md).
