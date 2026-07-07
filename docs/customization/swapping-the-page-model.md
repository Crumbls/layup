---
title: Swapping the Page Model
weight: 2
nav_title: Swapping the Page Model
order: 20
---

The bundled `Crumbls\Layup\Models\Page` works for most installations. Swap it when you need:

- A custom table name
- Per-panel page tables (multi-dashboard setups)
- Additional columns beyond what Layup ships with
- Custom scopes, accessors, or relationships
- Different soft-delete or auditing behavior

## Configure the swap

Set `pages.model` in `config/layup.php` to your subclass:

```php
// config/layup.php
'pages' => [
    'model' => \App\Models\MarketingPage::class,
    'table' => 'marketing_pages',
],
```

Layup uses this class everywhere it touches the page model -- the Filament resource, frontend routes, the revision system, the safelist auto-sync hook, and scheduled publishing.

## Extend the bundled model

Subclass `Crumbls\Layup\Models\Page` and override only what you need. The base model handles JSON casting, revision auto-save, scheduled publishing, and slug generation -- you inherit all of that.

```php
namespace App\Models;

use Crumbls\Layup\Models\Page as BasePage;

class MarketingPage extends BasePage
{
    protected $table = 'marketing_pages';

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
```

You will also need a migration to create the table. The simplest path is to publish Layup's migrations and adapt them, or write your own using the `layup_pages` schema as a reference.

## Multi-dashboard pattern

Two Filament panels, two completely separate page tables:

```php
// config/layup.php
'pages' => [
    // The "default" model -- used when no override is active.
    'model' => \App\Models\MarketingPage::class,
    'table' => 'marketing_pages',
],
```

Then, in each panel provider, ensure you publish a separate config or override the model registration if your panels run in the same process and you need different models per panel. For most setups, two distinct deployments (each with its own `config/layup.php`) is the cleaner pattern.

## When to use a fully custom model instead

If your "page" concept is very different from Layup's -- say, your model is `Article` with a publishing workflow that is unrelated to Layup's `published_at` semantics -- do not try to subclass `Page`. Use [Embedding the field](../embedding-the-field.md) instead, paired with [Disable the Pages resource](disable-pages-resource.md). You get the editor without inheriting Layup's CMS opinions.
