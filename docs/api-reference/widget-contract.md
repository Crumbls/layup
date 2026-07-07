---
title: Widget Contract
weight: 1
nav_title: Widget Contract
order: 10
---

All widgets implement `Crumbls\Layup\Contracts\Widget`. This interface defines the full API that the builder, registry, and renderer depend on. The interface is rendering-agnostic -- it specifies what a widget *is* and how it's *edited*, not how it produces HTML on the frontend. Two base classes implement the interface in concrete form:

- `Crumbls\Layup\View\BaseBladeWidget` -- renders through a Blade view component (the default).
- `Crumbls\Layup\View\BaseLivewireWidget` -- renders through a Livewire component (opt-in, requires `livewire/livewire`).

`Crumbls\Layup\View\BaseWidget` is an abstract alias for `BaseBladeWidget` retained for backwards compatibility. New code may extend either base directly. See [Livewire-rendered widgets](../customization/livewire-widgets.md) for when each base applies.

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

The interface deliberately omits `render()`. Each base class adds it with the appropriate return type (`View|Htmlable|string` for Blade, `string` for Livewire). The frontend call site stringifies whatever comes back, so any compliant return value works.

## BaseWidget / BaseBladeWidget

`Crumbls\Layup\View\BaseBladeWidget` provides the default Blade rendering path. Custom widgets may extend it directly, or extend `BaseWidget` (an abstract alias retained for backwards compatibility -- existing widgets and stubs need no changes).

**Minimum required overrides:**

- `getType()` -- unique string identifier
- `getLabel()` -- display name

**Common overrides:**

- `getContentFormSchema()` -- content tab fields
- `getDefaultData()` -- initial widget data
- `getPreview()` -- canvas preview text

**Render resolution:**

- Default: `view('layup::components.{type}')`. The `{type}` segment comes from `getType()`.
- Override `getViewName()` to point at a different view name.

## BaseLivewireWidget

`Crumbls\Layup\View\BaseLivewireWidget` renders through a Livewire component instead of a Blade view component. It implements the same `Widget` contract as `BaseBladeWidget` and shares the same metadata defaults via the `HasWidgetDefaults` trait.

**Minimum required overrides:**

- `getType()` -- unique string identifier
- `getLabel()` -- display name
- `getLivewireComponent()` -- Livewire alias (kebab-case) or fully-qualified class name

**Render mechanism:**

The base mounts the named Livewire component via `<livewire:dynamic-component>`, passing the widget's `$data` as a `data` prop and the recursively-rendered children as the default slot. See [Livewire-rendered widgets](../customization/livewire-widgets.md) for the full architecture, children-handling model, and migration patterns.

## HasWidgetDefaults trait

`Crumbls\Layup\View\Concerns\HasWidgetDefaults` holds the default implementations of every `Widget` interface method that doesn't depend on rendering tech: `getIcon()`, `getCategory()`, `getDefaultData()`, `getPreview()`, `prepareForRender()`, `getValidationRules()`, `getSearchTerms()`, `isDeprecated()`, `getDeprecationMessage()`, `onSave()`, `onCreate()`, `onDelete()`, `onDuplicate()`, `getAssets()`, `toArray()`. Used by both `BaseBladeWidget` and `BaseLivewireWidget`. You will not normally use this trait directly -- it's an implementation detail.

## Identity traits

`Crumbls\Layup\View\Concerns\Identity\` ships per-widget identity traits for select widgets that may have multiple render flavours. Each trait holds the static metadata for a widget -- type, label, icon, category, form schema, defaults, preview -- with no rendering logic. The traits are consumed by both the built-in Blade widget class and any downstream Livewire flavour:

```php
class HeadingWidget extends BaseWidget
{
    use HeadingIdentity; // sets type, label, icon, category, form, defaults, preview
}

// Downstream package, same trait, different render path:
class HeadingLivewireWidget extends BaseLivewireWidget
{
    use HeadingIdentity;

    public static function getLivewireComponent(): string
    {
        return 'my-package.heading';
    }
}
```

Identity traits currently shipped:

- `HeadingIdentity`
- `ButtonIdentity`
- `NumberCounterIdentity`
- `NewsletterIdentity`

For one-off widgets that will only ever ship in one render flavour, define the static methods directly on the widget class -- the trait pattern is overkill.

## BaseView

`Crumbls\Layup\View\BaseView` is the parent of `BaseBladeWidget`, `BaseLivewireWidget`, `Row`, `Column`, and `Section`. It provides:

- The three-tab form structure (Content, Design, Advanced)
- Shared Design tab fields (colors, borders, spacing, shadows, opacity)
- Shared Advanced tab fields (ID, classes, animations, visibility)
- `$data` and `$children` storage with fluent constructors and child-management methods
- Static helper methods:

```php
// Build Tailwind classes for responsive hiding
BaseView::visibilityClasses(['sm', 'lg']);

// Build inline CSS string from design data
BaseView::buildInlineStyles($data);

// Build Alpine.js animation attributes
BaseView::animationAttributes($data);
```

- Instance helper used by Livewire-rendered widgets and any custom render path:

```php
// Recursively render children to a single HTML string
$widget->renderChildrenToHtml();
```

- The abstract `render()` method whose return type is `View|Htmlable|string`. Subclasses may narrow the return type (Section/Row/Column return `View`; `BaseBladeWidget` returns `View|Htmlable|string`; `BaseLivewireWidget` returns `string`). The wide parent type lets both rendering paths satisfy the contract while preserving covariance.

## Type-checking widgets at runtime

When code inspects widgets at runtime (custom registries, content walkers, exporters, test assertions), prefer `instanceof Widget` (the interface) over `instanceof BaseWidget` (a class). The interface matches both Blade and Livewire bases; the class only matches the Blade lineage. Layup's own internals (`RegistersWidgets`, `LayupContent`, `LayupAssertions`, `WidgetDefaultCompletenessTest`) were switched to interface-based checks alongside the introduction of `BaseLivewireWidget` so that all rendering paths are equally discoverable.
