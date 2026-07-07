---
title: Revision History
weight: 4
nav_title: Revision History
order: 40
---

Layup automatically saves a revision snapshot every time a page's content changes. This gives you a full version history with the ability to restore any previous version.

## Configuration

```php
'revisions' => [
    'enabled' => true,
    'max' => 50,
],
```

- **enabled** -- toggle automatic revision creation
- **max** -- maximum revisions to keep per page. When the limit is reached, the oldest revisions are pruned automatically.

## How it works

The `Page` model's `saved` event checks if the `content` column changed. If so, it calls `saveRevision()` which:

1. Creates a `PageRevision` record with the current content, timestamp, and authenticated user
2. Prunes old revisions if the count exceeds the configured max

## The PageRevision model

Each revision stores:

| Column | Type | Description |
|--------|------|-------------|
| `id` | integer | Primary key |
| `page_id` | foreignId | Parent page (cascade delete) |
| `content` | json | Full content snapshot |
| `note` | string (nullable) | Optional revision note |
| `author` | string (nullable) | User name or email who saved |
| `created_at` | timestamp | When the revision was created |

## Working with revisions

```php
use Crumbls\Layup\Models\Page;

$page = Page::find(1);

// Get all revisions (newest first)
$revisions = $page->revisions;

// Save a revision with a note
$page->saveRevision('Before major redesign');

// Restore a specific revision
$revision = $page->revisions()->first();
$page->restoreRevision($revision);
```

`restoreRevision()` sets the page content to the revision's content and saves the page, which in turn creates a new revision of the restored state.
