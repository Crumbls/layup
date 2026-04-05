---
title: Support Classes
weight: 4
---

## WidgetRegistry

`Crumbls\Layup\Support\WidgetRegistry`

Singleton service that manages all registered widgets.

```php
use Crumbls\Layup\Support\WidgetRegistry;

$registry = app(WidgetRegistry::class);

// Register / unregister
$registry->register(MyWidget::class);
$registry->unregister('my-widget');

// Lookup
$class = $registry->get('heading');     // Returns class string or null
$exists = $registry->has('heading');    // bool
$all = $registry->all();               // ['heading' => HeadingWidget::class, ...]

// Grouped by category
$grouped = $registry->grouped();
// ['content' => [...], 'media' => [...], ...]

// Delegate to widget class
$schema = $registry->getFormSchema('heading');
$defaults = $registry->getDefaultData('heading');
$preview = $registry->getPreview('heading', $data);

// Lifecycle events
$registry->fireOnCreate('heading', $data, $context);
$registry->fireOnSave('heading', $data, $context);
$registry->fireOnDelete('heading', $data, $context);
$registry->fireOnDuplicate('heading', $data, $context);

// Alpine.js data (cached)
$jsData = $registry->toJs();
```

## ContentWalker

`Crumbls\Layup\Support\ContentWalker`

Utilities for traversing the content JSON structure.

```php
use Crumbls\Layup\Support\ContentWalker;

// Walk every widget in a content array
ContentWalker::walkWidgets($content, function (string $type, array $data, array $path) {
    // $path = ['sections', 0, 'rows', 1, 'columns', 0, 'widgets', 2]
});

// Count widget types
$counts = ContentWalker::collectWidgetTypes($content);
// ['text' => 3, 'heading' => 2, 'image' => 1]

// Flatten sections into rows
$rows = ContentWalker::extractRows($content);
```

## ContentValidator

`Crumbls\Layup\Support\ContentValidator`

Validates content structure and widget data.

```php
use Crumbls\Layup\Support\ContentValidator;

$validator = new ContentValidator();
$result = $validator->validate($content);

$result->passes();  // bool
$result->errors();  // array of error messages
```

## WidgetData

`Crumbls\Layup\Support\WidgetData`

Type-safe accessor for widget data in Blade templates. Implements `ArrayAccess`.

```php
use Crumbls\Layup\Support\WidgetData;

$data = new WidgetData($rawArray);

$data->string('title', 'Default');
$data->bool('show_icon', false);
$data->int('count', 0);
$data->float('rating', 0.0);
$data->array('items', []);
$data->has('title');
$data->storageUrl('image');  // asset('storage/' . $path)
$data->url('link');          // Validated URL string
```

## WidgetContext

`Crumbls\Layup\Support\WidgetContext`

Context object passed to lifecycle hooks.

```php
use Crumbls\Layup\Support\WidgetContext;

// Properties (all readonly, nullable)
$context->page;      // Page model
$context->rowId;     // Row identifier
$context->columnId;  // Column identifier
$context->widgetId;  // Widget identifier
```

## FieldPacks

`Crumbls\Layup\Support\FieldPacks`

Reusable field groups for widget form schemas.

```php
use Crumbls\Layup\Support\FieldPacks;

// Image upload + alt text
FieldPacks::image('hero');
// Returns: FileUpload('hero_src'), TextInput('hero_alt')

// URL + new tab toggle
FieldPacks::link('cta');
// Returns: TextInput('cta_url'), Toggle('cta_new_tab')

// Two color pickers
FieldPacks::colorPair('bg_color', 'text_color');

// Background, hover bg, text, hover text color pickers
FieldPacks::hoverColors('button');
```

## HasLayupContent trait

`Crumbls\Layup\Concerns\HasLayupContent`

Add Layup rendering to any Eloquent model:

```php
use Crumbls\Layup\Concerns\HasLayupContent;

class Article extends Model
{
    use HasLayupContent;

    // Default column is 'content'. Override if needed:
    protected string $layupContentColumn = 'body';
}
```

This gives the model:

```php
$article->toHtml();            // Render content to HTML string
$article->getContentTree();    // Array of Row objects
$article->getSectionTree();    // Array of sections with Row objects
$article->getUsedClasses();    // CSS classes in content
$article->getUsedInlineStyles(); // Inline CSS declarations
```

## LayupTheme

`Crumbls\Layup\Support\LayupTheme`

Generates CSS custom properties for the theme. Usually configured through `LayupPlugin`, but can be used directly:

```php
$theme = app(LayupTheme::class);

$theme->colors(['primary' => '#3b82f6']);
$theme->darkColors(['primary' => '#60a5fa']);
$theme->fonts(['heading' => 'Playfair Display, serif']);
$theme->borderRadius('0.5rem');

// Get generated CSS
$css = $theme->toCss();
```

Generated CSS includes `:root` and `.dark` blocks with `--layup-*` custom properties, plus utility classes like `.layup-bg-primary`, `.layup-text-primary`, `.layup-border-primary`, and hover variants.
