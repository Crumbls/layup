---
title: Saving and Publishing
weight: 3
---

## Saving

Click the Filament **Save** button to persist your page. On every save:

1. Content is validated structurally (rows have columns, columns have widgets, widgets have types)
2. Widget-level validation runs (e.g., buttons need a label and URL)
3. If revisions are enabled, a revision snapshot is created automatically
4. If safelist auto-sync is enabled, the Tailwind safelist file is regenerated

Validation warnings are logged but do not block the save. This lets editors save work in progress without losing data.

## Publishing

Set the **Status** field to `published` to make the page live. Draft pages are only visible in the admin panel.

### Scheduled publishing

Set the status to `scheduled` and pick a future **Publish at** date and time. Layup auto-registers the `layup:publish-scheduled` console command on the application scheduler (every minute), so the page flips to `published` once its publish time arrives without any extra wiring -- as long as your app's scheduler is actually running (`php artisan schedule:work` in development, the standard cron in production).

If you'd rather control the command yourself (for example, run it on a single worker), set `layup.scheduling.auto_publish` to `false` in `config/layup.php` and call `layup:publish-scheduled` from your own scheduler. The command supports `--dry-run` to preview which pages would be promoted:

```bash
php artisan layup:publish-scheduled --dry-run
```

### Frontend routes

When `frontend.enabled` is `true` in `config/layup.php` (the default), published pages are served at:

```
/{prefix}/{slug}
```

The default prefix is `pages`, so a page with slug `about-us` would be at `/pages/about-us`.

Configure the prefix, middleware, domain, layout, and view in the `frontend` section of the config:

```php
'frontend' => [
    'enabled' => true,
    'prefix' => 'pages',
    'middleware' => ['web'],
    'domain' => null,
    'layout' => 'app',
    'view' => 'layup::frontend.page',
    'max_width' => 'container',
    'include_scripts' => true,
    'excluded_paths' => [],
],
```

### SEO metadata

Each page supports meta fields stored in the `meta` JSON column:

- `title` -- overrides the page title for `<title>` tag
- `description` -- meta description (160 character limit in the form)
- `keywords` -- meta keywords

The frontend view receives `$page` and `$meta` variables. The `Page` model provides helper methods:

```php
$page->getMetaTitle();       // Falls back to page title
$page->getMetaDescription();
$page->getMetaKeywords();
$page->getMetaTags();        // Array of all meta tags
$page->getStructuredData();  // JSON-LD (WebPage, Article, FAQPage, BreadcrumbList)
```

### Structured data

Set `meta.schema_type` to control the JSON-LD type:

- `WebPage` (default)
- `Article` or `BlogPosting` (includes author if `meta.author` is set)
- `FAQPage` (auto-extracts questions from Accordion and Toggle widgets)

Breadcrumb structured data is generated automatically from the page slug.

## Duplicating pages

In the page list, use the **Duplicate** action to create a draft copy with a randomized slug. This is useful for creating variations of existing pages.

## Exporting and importing

Export a page to JSON:

```bash
php artisan layup:export --slug=about-us
```

Import from a JSON file:

```bash
php artisan layup:import path/to/page.json
```
