---
title: Artisan commands
nav_title: Commands
weight: 50
order: 50
---

# Artisan commands

Layup registers these commands from `Crumbls\Layup\LayupServiceProvider`.

| Command | Purpose |
| --- | --- |
| `layup:install` | Publishes config, runs migrations, creates the storage link, publishes Filament assets, generates the safelist, and prints setup checks. |
| `layup:doctor` | Diagnoses common installation issues. |
| `layup:audit` | Audits saved pages for broken widgets, unused classes, and content issues. |
| `layup:safelist {--output=} {--stdout} {--static-only}` | Generates the Tailwind safelist from static widget classes and, unless `--static-only` is used, saved page content. |
| `layup:list-widgets {--category=} {--custom-only}` | Lists registered widgets with type, label, category, and source. |
| `layup:debug-widget {type} {--data=}` | Dumps resolved metadata, defaults, merged data, validation rules, assets, and search tags for a widget. |
| `layup:make-widget {name} {--with-test}` | Scaffolds a widget class, Blade view, and optional Pest test. |
| `layup:make-controller {name}` | Scaffolds a controller extending `Crumbls\Layup\Http\Controllers\AbstractController`. |
| `layup:export {--output=} {--status=} {--pretty}` | Exports Layup pages as JSON. |
| `layup:import {file} {--dry-run} {--overwrite}` | Imports pages from a JSON export file. |
| `layup:search {type?} {--unused}` | Finds pages using a widget type, or registered widget types not used by any page. |
| `layup:publish-scheduled {--dry-run}` | Promotes scheduled pages whose `published_at` time has passed. |

## Common command flows

```bash
php artisan layup:install
php artisan layup:doctor
php artisan layup:safelist
php artisan layup:list-widgets
php artisan layup:debug-widget heading
php artisan layup:export --output=storage/app/layup-pages.json --pretty
php artisan layup:import storage/app/layup-pages.json --dry-run
```
