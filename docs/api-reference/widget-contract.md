---
title: Widget Contract
weight: 1
---

All widgets implement `Crumbls\Layup\Contracts\Widget`. This interface defines the full API that the builder, registry, and renderer depend on.

## Interface methods

```php
namespace Crumbls\Layup\Contracts;

use Crumbls\Layup\Support\WidgetContext;

interface Widget
{
    // Identity
    public static function getType(): string;
    public static function getLabel(): string;
    public static function getIcon(): string;
    public static function getCategory(): string;

    // Form & data
    public static function getFormSchema(): array;
    public static function getDefaultData(): array;
    public static function getValidationRules(): array;

    // Rendering
    public static function getPreview(array $data): string;
    public static function prepareForRender(array $data): array;

    // Discovery
    public static function getSearchTerms(): array;
    public static function isDeprecated(): bool;
    public static function getDeprecationMessage(): string;

    // Lifecycle
    public static function onSave(array $data, ?WidgetContext $context = null): array;
    public static function onCreate(array $data, ?WidgetContext $context = null): array;
    public static function onDelete(array $data, ?WidgetContext $context = null): void;
    public static function onDuplicate(array $data, ?WidgetContext $context = null): array;

    // Assets & serialization
    public static function getAssets(): array;
    public static function toArray(): array;
}
```

## BaseWidget

`Crumbls\Layup\View\BaseWidget` provides sensible defaults for every method. Custom widgets should extend `BaseWidget` and override only what they need.

**Minimum required overrides:**

- `getType()` -- unique string identifier
- `getLabel()` -- display name

**Common overrides:**

- `getContentFormSchema()` -- content tab fields
- `getDefaultData()` -- initial widget data
- `getPreview()` -- canvas preview text

## BaseView

`Crumbls\Layup\View\BaseView` is the parent of `BaseWidget`, `Row`, `Column`, and `Section`. It provides:

- The three-tab form structure (Content, Design, Advanced)
- Shared Design tab fields (colors, borders, spacing, shadows, opacity)
- Shared Advanced tab fields (ID, classes, animations, visibility)
- Static helper methods:

```php
// Build Tailwind classes for responsive hiding
BaseView::visibilityClasses(['sm', 'lg']);

// Build inline CSS string from design data
BaseView::buildInlineStyles($data);

// Build Alpine.js animation attributes
BaseView::animationAttributes($data);
```
