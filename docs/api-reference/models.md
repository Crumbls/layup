---
title: Models
weight: 2
nav_title: Models
order: 20
---

## Page

`Crumbls\Layup\Models\Page`

The main model for Layup pages. Uses soft deletes, factory support, and the `HasLayupContent` trait.

### Attributes

| Column | Type | Description |
|--------|------|-------------|
| `id` | integer | Primary key |
| `parent_id` | foreignId, nullable | Parent page (cascade nulls on delete) |
| `title` | string | Page title |
| `slug` | string | URL slug, indexed (no longer unique on its own) |
| `path` | string | Resolved URL path (unique). Built from the parent chain on save |
| `content` | array (JSON) | Page builder content |
| `status` | string | `draft`, `scheduled`, or `published` |
| `published_at` | datetime, nullable | Live time (auto-set on publish, used for scheduled publishing) |
| `meta` | array (JSON) | SEO metadata |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |
| `deleted_at` | timestamp | Soft delete |

### Scopes

```php
Page::published()->get();  // Where status = 'published'
Page::draft()->get();      // Where status = 'draft'
```

### Relationships

```php
$page->parent(): BelongsTo   // Parent page (or null for top-level)
$page->children(): HasMany   // Direct children
```

### SEO methods

```php
$page->getMetaTitle(): string
$page->getMetaDescription(): ?string
$page->getMetaKeywords(): ?string
$page->getMetaTags(): array
$page->getStructuredData(): array  // JSON-LD schemas
```

### Revision methods

```php
$page->revisions(): HasMany
$page->saveRevision(?string $note = null): PageRevision
$page->restoreRevision(PageRevision $revision): void
```

### URL and rendering

```php
$page->getUrl(): string        // Frontend URL based on config prefix
$page->toHtml(): string        // Render content to HTML (from HasLayupContent)
$page->getContentTree(): array // Get Row/Column/Widget object tree
$page->getUsedClasses(): array // CSS classes in content
```

### Static methods

```php
Page::sitemapEntries(): array  // All published pages as sitemap data
```

### Customization

The table name is configurable:

```php
// config/layup.php
'pages' => [
    'table' => 'layup_pages',
    'model' => \Crumbls\Layup\Models\Page::class,
],
```

To extend the model, create your own and update the config:

```php
namespace App\Models;

use Crumbls\Layup\Models\Page as BasePage;

class Page extends BasePage
{
    // Custom behavior
}
```

## PageRevision

`Crumbls\Layup\Models\PageRevision`

Stores content snapshots for version history.

### Attributes

| Column | Type | Description |
|--------|------|-------------|
| `id` | integer | Primary key |
| `page_id` | foreignId | Parent page |
| `content` | array (JSON) | Content snapshot |
| `note` | string (nullable) | Revision note |
| `author` | string (nullable) | Who saved it |
| `created_at` | timestamp | |

### Relationships

```php
$revision->page(): BelongsTo  // Parent page
```
