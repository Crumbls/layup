---
title: Page Templates
weight: 5
nav_title: Page Templates
order: 50
---

# Page templates

Templates provide pre-built content for new pages.

## Create a page from a template

Open **Pages**, click **New Page**, choose a template under **Start from template**, enter a title, and submit. The new draft opens in the editor with a copy of the selected template's content. Both built-in templates and custom templates saved from existing pages appear in the selector.

Leave the selector empty or choose **Blank Page** to create an empty page. If you change or clear the selection before submitting, the final selection determines the content.

Fixed in v1.7.0: selecting a template now preserves its content when creating a page. Pages previously created empty are not changed by upgrading.

## Built-in templates

Layup ships with five templates stored as JSON files in `resources/templates/`:

- **Blank** -- empty page with no content
- **Landing** -- landing page with hero, features, and CTA sections
- **About** -- about page layout
- **Contact** -- contact page with form
- **Pricing** -- pricing tiers layout

## Creating custom templates

### From an existing page

Save any page's content as a reusable template:

```php
use Crumbls\Layup\Support\PageTemplate;

PageTemplate::saveFromPage(
    name: 'Product Page',
    content: $page->content,
    description: 'Standard product landing page',
);
```

This writes a JSON file to `resources/layup/templates/product-page.json`.

### Manually

Create a JSON file in `resources/layup/templates/`:

```json
{
    "name": "My Template",
    "description": "Description shown in the template picker",
    "thumbnail": null,
    "content": {
        "rows": [
            {
                "id": "row-1",
                "settings": {},
                "columns": [
                    {
                        "id": "col-1",
                        "span": {"sm": 12, "md": 12, "lg": 12, "xl": 12},
                        "settings": {},
                        "widgets": [
                            {
                                "id": "widget-1",
                                "type": "heading",
                                "data": {"content": "Page Title", "level": "h1"}
                            }
                        ]
                    }
                ]
            }
        ]
    }
}
```

Publish the templates directory first:

```bash
php artisan vendor:publish --tag=layup-templates
```

## Using templates programmatically

```php
use Crumbls\Layup\Support\PageTemplate;

// Get all templates (built-in + custom)
$templates = PageTemplate::all();

// Get a single template by slug
$template = PageTemplate::get('landing-page');
// Includes: ['name' => 'Landing Page', 'description' => '...', 'content' => [...]]

// Get options for a select dropdown
$options = PageTemplate::options();
// Includes: ['blank' => 'Blank Page', 'landing-page' => 'Landing Page', ...]
```
