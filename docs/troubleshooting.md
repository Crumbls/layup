---
title: Troubleshooting
nav_title: Troubleshooting
weight: 100
order: 140
---

# Troubleshooting

Start with the package diagnostics:

```bash
php artisan layup:doctor
php artisan layup:audit
```

`layup:doctor` checks installation problems. `layup:audit` checks saved page content for broken widgets, unused classes, and content issues.

## The Pages resource is missing

Check that the plugin is registered in the Filament panel provider and that `pages.enabled` is `true` in `config/layup.php`.

If `pages.enabled` is `false`, Layup still provides the field and renderer, but it does not register the bundled Pages resource.

## A published page returns 404

Published frontend pages must pass the `published()` scope:

- `status` must be `published`.
- `published_at` must be empty or in the past.
- `path` must match the requested path.

If the page has a future `published_at`, it is scheduled and will not render until `layup:publish-scheduled` promotes it.

## Scheduled pages do not publish

The service provider registers `layup:publish-scheduled` every minute when `scheduling.auto_publish` is `true`, but Laravel's scheduler still needs to run in the host application.

```bash
php artisan layup:publish-scheduled --dry-run
php artisan layup:publish-scheduled
```

## Styling is missing on the frontend

Generate the safelist and include it in Tailwind:

```bash
php artisan layup:safelist
```

For Tailwind v4:

```css
@source "../../storage/layup-safelist.txt";
```

For Tailwind v3, include `./storage/layup-safelist.txt` in `content`.

If your app uses field-only mode, add each host model to `safelist.content_sources` and sync the safelist from its model events. Run the safelist command in your build pipeline as well.

## A widget appears blank

Check that the widget type in saved content is registered, the widget class exists, `getType()` matches the saved type, and the Blade view filename matches the widget type.

```bash
php artisan layup:debug-widget widget-type
php artisan layup:list-widgets
```

## A custom widget does not appear in the picker

By default, auto-discovery scans `App\Layup\Widgets`. If the widget lives elsewhere, update `widget_discovery` in config or register it through `LayupPlugin::make()->widgets([...])`.

## Upload URLs are broken

Layup uses `uploads.disk`, which defaults to `public`. For the default disk, make sure the storage symlink exists:

```bash
php artisan storage:link
```

## Import skips pages

`layup:import` validates that the JSON contains a `pages` array and that each page has a slug. Existing pages are skipped unless `--overwrite` is provided.

```bash
php artisan layup:import storage/app/layup-pages.json --dry-run
php artisan layup:import storage/app/layup-pages.json --overwrite
```
