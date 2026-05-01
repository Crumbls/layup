---
title: Livewire-rendered widgets
weight: 4
---

By default, Layup widgets render through Blade view components. Frontend output for `HeadingWidget` resolves to `layup::components.heading`, the data array is passed as `$data`, and the result is plain HTML. This is the right model for static content -- headings, images, text, buttons -- where there is nothing to react to and no server-side state to maintain.

For widgets that need state, server actions, or reactive UI -- counters, contact forms, search-as-you-type, polling notifiers, anything stateful -- you can render through a Livewire component instead. The Widget contract is unchanged: form schema, defaults, lifecycle hooks, preview, and registration all work identically. Only the frontend render path differs.

This guide covers when to choose which base, the architecture that makes both bases interchangeable, the slot-based children model, the identity-trait pattern that lets a downstream package swap a built-in widget's render path without redeclaring its editor experience, and the operational considerations -- assets, performance, testing, security -- that come with each path.

## Architecture overview

Three concerns live in three orthogonal places:

1. **Identity & editor experience** -- the static metadata that defines a widget: type, label, icon, category, form schema, default data, preview, validation rules, search terms, lifecycle hooks. This is the contract the editor and registry care about.
2. **Rendering technology** -- how the widget produces HTML for the frontend. Either a Blade view component (the default) or a Livewire component (opt-in).
3. **Data plumbing** -- the runtime mechanics of `$data`, `$children`, position-in-parent, and recursive child rendering.

Identity lives in either the widget class itself or, for widgets meant to ship in multiple render flavours, an identity trait. Rendering tech lives in the base class (`BaseBladeWidget` or `BaseLivewireWidget`). Data plumbing lives in `BaseView`, which both bases extend. The `Widget` contract (`Crumbls\Layup\Contracts\Widget`) is the interface both bases implement, so anywhere code accepts "a widget" it accepts either flavour.

```text
Crumbls\Layup\Contracts\Widget               (interface, rendering-agnostic)
                |
                v
Crumbls\Layup\View\BaseView                  (data plumbing: $data, $children)
                |
        +-------+--------+
        |                |
BaseBladeWidget    BaseLivewireWidget
        |                |
   (your widget)    (your widget)

BaseWidget = abstract alias for BaseBladeWidget (back-compat shim).
```

The editor-side machinery -- form rendering, builder canvas previews, the WidgetRegistry, exports/imports, content serialization, `assertWidgetRenders()` in tests -- treats both bases identically because all checks go through the `Widget` interface, not a specific base class.

## When to choose which

| Concern | `BaseWidget` (Blade, default) | `BaseLivewireWidget` |
|---|---|---|
| Static content (heading, image, text, divider) | Yes | Overkill |
| Server-rendered HTML, no interactivity | Yes | Overkill |
| Alpine-only interactivity (toggles, modals, animations) | Yes | Overkill |
| Form submissions handled server-side | Awkward (need separate endpoint) | Yes |
| Server-side state that survives across actions (counters, multi-step flows) | Not feasible | Yes |
| Polling for data (live ticker, notification feed) | Possible via `wire:poll` only inside a Livewire component | Yes |
| Search-as-you-type with debounced server query | Yes | Yes |
| Auth-aware UI (logged-in user data) | Yes (server-rendered) | Yes |
| Rows, columns, sections (structural containers) | Required | Not supported |

**Use the default Blade base unless you have a concrete reason to upgrade.** Livewire adds a per-widget hydration cost on every page render, an extra round-trip on every action, and an extra dependency. If a widget has no state and no server actions, none of that pays for itself.

Container widgets (`Section`, `Row`, `Column`) are not eligible for Livewire rendering because they recurse over `$children` as PHP `BaseView` objects -- Livewire props would have to serialise that object graph across requests, which is not a thing Livewire props do. Leaf widgets (any `BaseWidget` subclass that doesn't accept children) are free to choose. See [Children inside a Livewire widget](#children-inside-a-livewire-widget) below for how children flow through Livewire-rendered widgets that *do* have children -- the slot pattern handles it cleanly.

## Anatomy of a Livewire-rendered widget

A Livewire widget is two pieces: the **widget class** (Layup-side, owns editor identity) and the **Livewire component** (Livewire-side, owns runtime behaviour). The widget class declares the Livewire component name via `getLivewireComponent()`; the rest of the class looks identical to any Blade widget.

### The widget class

```php
<?php

declare(strict_types=1);

namespace App\Layup\Widgets;

use Crumbls\Layup\View\BaseLivewireWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class LiveCounterWidget extends BaseLivewireWidget
{
    public static function getType(): string
    {
        return 'live-counter';
    }

    public static function getLabel(): string
    {
        return 'Live Counter';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-bolt';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getLivewireComponent(): string
    {
        return 'live-counter'; // alias registered with Livewire
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('label')->required(),
            TextInput::make('start')->numeric()->default(0),
            Toggle::make('animate')->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'label' => 'Clicks',
            'start' => 0,
            'animate' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        return "Counter: {$data['label']}";
    }
}
```

The shape is the same as any Layup widget: required `getType()` and `getLabel()`, optional metadata overrides, `getContentFormSchema()` for the editor form, `getDefaultData()` for new instances. The one Livewire-specific addition is `getLivewireComponent()`, which returns either a Livewire component alias (kebab-case) or a fully-qualified class name.

### The Livewire component

The Livewire component is whatever you'd write normally. It receives the widget data as a `$data` prop (typed as `array`) and a `$slot` containing the pre-rendered children HTML.

```php
<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;

class LiveCounter extends Component
{
    public array $data = [];
    public int $count = 0;

    public function mount(array $data): void
    {
        $this->data = $data;
        $this->count = (int) ($data['start'] ?? 0);
    }

    public function increment(): void
    {
        $this->count++;
    }

    public function reset(): void
    {
        $this->count = (int) ($this->data['start'] ?? 0);
    }

    public function render()
    {
        return view('livewire.live-counter');
    }
}
```

```blade
{{-- resources/views/livewire/live-counter.blade.php --}}
<div class="layup-live-counter">
    <p class="text-2xl font-semibold">
        {{ $data['label'] }}: {{ $count }}
    </p>
    <div class="flex gap-2 mt-2">
        <button type="button" wire:click="increment">+1</button>
        <button type="button" wire:click="reset">Reset</button>
    </div>
    {{-- Children, if any. Static unless replaced by another Livewire flow. --}}
    {{ $slot }}
</div>
```

Register the widget like any other:

```php
// config/layup.php
'widgets' => [
    \App\Layup\Widgets\LiveCounterWidget::class,
    // ...
],
```

Or programmatically in a service provider:

```php
app(\Crumbls\Layup\Support\WidgetRegistry::class)
    ->register(\App\Layup\Widgets\LiveCounterWidget::class);
```

That's the full integration. The widget shows up in the editor with its form schema, the editor saves data into the page JSON the same way it always has, and on the frontend the page renders a `<livewire:live-counter>` tag with the data prop populated. Livewire takes over from there.

## How the render flow works

When the frontend renders a page, Layup walks the content tree section by section. Inside a section, rows render their columns, columns render their widgets. Each widget's `render()` method is called and its output is echoed via `{!! ... !!}`.

For a Blade-rendered widget (`BaseBladeWidget::render()`), this returns a Blade `View` instance, which stringifies to HTML. For a Livewire-rendered widget (`BaseLivewireWidget::render()`), this returns a `string` produced by `Blade::render('<livewire:dynamic-component :component="$component" :data="$data">{!! $childrenHtml !!}</livewire:dynamic-component>', [...])`. Both results are valid output for the call site -- the parent template doesn't care which path produced them.

The `<livewire:dynamic-component>` tag is the standard Livewire 3 mechanism for mounting a component when the name is determined at runtime. The component name comes from `static::getLivewireComponent()`, the data prop comes from the widget's `$this->data`, and the slot content comes from `BaseView::renderChildrenToHtml()` -- which recursively calls `render()` on each child and concatenates the results.

A Livewire `wire:key` is set automatically based on the widget type and `spl_object_id($this)`. This is unique within a single request, which is enough for Livewire to distinguish sibling widgets of the same type. It is **not** stable across requests, so if your widget needs persistent identity across re-renders (rare) you'll want to override `render()` and supply your own key derived from the widget's persisted data.

## Children inside a Livewire widget

`BaseLivewireWidget::render()` recursively renders the widget's children to a single HTML string and passes the result as the Livewire slot. Inside the Livewire component's view, `{{ $slot }}` emits children wherever they belong.

```blade
{{-- A Livewire-rendered tabs widget --}}
<div x-data="{ active: @entangle('active') }">
    <nav class="tabs-nav">
        @foreach ($data['tabs'] ?? [] as $i => $tab)
            <button wire:click="$set('active', {{ $i }})">{{ $tab['label'] }}</button>
        @endforeach
    </nav>
    <div class="tabs-content">
        {{ $slot }}
    </div>
</div>
```

Children remain polymorphic. A Livewire-rendered widget can contain Blade-rendered children, other Livewire-rendered children, or any mix. Each child manages its own rendering independently. Nested Livewire components mount with their own `wire:id` and rehydrate independently of the parent.

When the parent Livewire component re-renders in response to its own action (`wire:click`, `wire:submit`, etc.), the slot content is preserved by Livewire -- children are not re-executed on the server. This is the right default: the structural data in children belongs to the persisted page content, not to the parent's reactive state. If you need children to react to the parent's state, use Livewire events to talk between components rather than expecting the slot to recompute.

## Identity traits: shipping multiple flavours of the same widget

Layup ships identity traits in `Crumbls\Layup\View\Concerns\Identity\` for select widgets. An identity trait holds the static metadata for a widget -- type, label, icon, category, form schema, defaults, preview -- with no rendering logic. The traits exist so a downstream package can implement a Livewire flavour of the same widget without redeclaring the editor experience.

Built-in widgets that already use identity traits:

- `HeadingWidget` -- `HeadingIdentity`
- `ButtonWidget` -- `ButtonIdentity`
- `NumberCounterWidget` -- `NumberCounterIdentity`
- `NewsletterWidget` -- `NewsletterIdentity`

Each of those widget classes is now a four-line shell. For example:

```php
class NewsletterWidget extends BaseWidget
{
    use NewsletterIdentity;
}
```

A downstream package can ship a Livewire flavour by extending `BaseLivewireWidget` and pulling in the same trait:

```php
<?php

declare(strict_types=1);

namespace MyPackage\Widgets;

use Crumbls\Layup\View\BaseLivewireWidget;
use Crumbls\Layup\View\Concerns\Identity\NewsletterIdentity;

class NewsletterLivewireWidget extends BaseLivewireWidget
{
    use NewsletterIdentity;

    public static function getLivewireComponent(): string
    {
        return 'my-package.newsletter';
    }
}
```

Same `getType()` returns `'newsletter'`, same form, same defaults, same preview. Different render path. Register against the same `WidgetRegistry` and the Livewire flavour overrides the Blade flavour:

```php
app(\Crumbls\Layup\Support\WidgetRegistry::class)
    ->register(\MyPackage\Widgets\NewsletterLivewireWidget::class);
```

`WidgetRegistry::register()` logs a warning when overriding an existing type so the swap is visible in logs:

```text
Layup: Widget type 'newsletter' already registered by ... Overriding with ...
```

Adding identity traits to your own widgets is optional. Use one only when you intend to ship multiple render flavours of the same widget. For one-off widgets, define the static methods directly on the class -- it's less ceremony.

## Migrating an existing custom widget

If you already have a Blade-rendered custom widget and want to add a Livewire flavour for an interactive variant:

1. Extract the widget's static metadata (everything from `getType()` through `getPreview()`, plus any custom `getValidationRules()`, `getSearchTerms()`, lifecycle hooks) into a trait. Keep the trait in `App\Layup\Widgets\Concerns\` or wherever fits your namespace.
2. Replace those methods on the original widget class with `use FooIdentity;`. Run your tests -- they should all still pass; nothing about the public surface has changed.
3. Create a new Livewire-flavoured widget class extending `BaseLivewireWidget` that uses the same trait and adds `getLivewireComponent()`.
4. Build the Livewire component (separate file, standard Livewire 3 component class plus a Blade view).
5. Decide whether the Livewire flavour replaces the Blade flavour everywhere or coexists with it. If replacing, register the Livewire flavour with `WidgetRegistry` -- the same `getType()` will trigger the override warning. If coexisting, give the Livewire flavour a different `getType()` (e.g. `newsletter-live`) so both show up in the editor as distinct widgets.

## Asset pipeline

Layup widgets can declare JavaScript and CSS dependencies via `getAssets()`. Those declarations are picked up at registry time and injected into the page when the widget is present. For example:

```php
public static function getAssets(): array
{
    return [
        'js' => ['/vendor/my-package/counter.js'],
        'css' => ['/vendor/my-package/counter.css'],
    ];
}
```

For a Livewire widget, you have two asset pipelines in play: Layup's `getAssets()` and Livewire's own asset injection (Livewire styles and scripts). Recommended approach:

- Use Livewire's pipeline for any JS or CSS that ships with the Livewire component itself. Livewire 3's `@livewireStyles` / `@livewireScripts` are already in place if the host app uses Livewire normally, and `@assets` blocks inside Livewire component views work as expected.
- Use Layup's `getAssets()` for assets that should load only when the widget is present on a page, regardless of Livewire. For pure-Livewire widgets you can usually return `[]`.

There's no conflict between the two -- they target different injection points. Just don't double-register the same file in both.

## Editor preview vs. frontend render

The Livewire path runs only on the frontend. In the builder canvas, widget previews come from the static `getPreview(array $data)` method, which returns a plain string -- Livewire is not involved. This is intentional: the editor canvas would have to mount and tear down a Livewire instance per widget per state change, which would make the builder slow and fragile. A static preview is enough for the editor.

If you want a richer-looking preview in the canvas, override `getPreview()` to return a more detailed string or use a custom canvas preview view. The Livewire component is for the published page only.

## Performance considerations

Each Livewire-rendered widget on a page incurs:

- Server-side: one `Blade::render()` call to mount the component, one `mount()` invocation, one component `render()` invocation.
- Client-side: a `wire:id` wrapper and Livewire's morphdom diffing on every action.
- Network: every action that touches the component sends a request to Livewire's backend endpoint.

For pages with one or two interactive widgets among mostly static content, this is unnoticeable. For pages where every widget is Livewire, the overhead adds up -- both initial render time and JS bundle weight. The default Blade base is the right choice for static widgets because it has none of these costs.

If you find yourself reaching for Livewire on every widget, consider whether the page-builder model is the right tool for that page. Genuinely application-shaped UIs (multi-step wizards, dashboards, complex forms) are usually better served by hand-built Livewire components or full SPAs that consume Layup data via API rather than by rendering through the page builder.

## Testing

Layup's test helpers in `Crumbls\Layup\Testing\LayupAssertions` work for both bases. The two assertions that exercise rendering -- `assertWidgetRenders($type, $data)` and `assertWidgetRendersWithDefaults($class)` -- coerce whatever `render()` returns (View, Htmlable, or string) into a string before asserting non-empty output. So:

```php
$this->assertWidgetRenders('live-counter');
$this->assertWidgetRendersWithDefaults(LiveCounterWidget::class);
```

both work.

For deeper Livewire-specific testing (actions, state transitions, events), use Livewire's own test helpers (`Livewire::test('live-counter', ['data' => [...]])` and friends) and write the tests against the Livewire component class, not the Layup widget class. The widget class is just a thin adapter -- it has no behaviour to test beyond its static metadata, which `assertWidgetContractValid($class)` already covers.

When asserting that a Livewire-rendered page renders correctly end-to-end, install `livewire/livewire` in your test environment (it ships its own service provider that registers required routes and middleware). Without it, the page render will fail on the `<livewire:dynamic-component>` tag.

## Security

Two things to watch for:

1. **Data prop content**: the widget's saved `$data` array is passed verbatim to the Livewire component's `mount()`. If editors have been allowed to put HTML in any field, that HTML is the Livewire component's responsibility to escape. `{{ $data['label'] }}` in Blade auto-escapes; `{!! $data['raw_html'] !!}` does not. Treat editor-provided strings as untrusted unless your editor explicitly sanitises them.
2. **Action authorisation**: any `wire:click` or `wire:submit` handler on a Livewire widget can be invoked by anyone who can load the page that contains the widget. If the action mutates state or performs work that should be authenticated, gate it inside the Livewire component (`if (! Auth::check()) { abort(403); }`). The page builder doesn't provide implicit authorisation -- the page is public.

## Installation

`livewire/livewire` is a soft dependency declared in Layup's `composer.json` `suggest` block. Layup loads cleanly without it -- you only need to install it if you actually use `BaseLivewireWidget`:

```bash
composer require livewire/livewire
```

Once installed, Livewire's standard setup applies: the package registers its own service provider, routes, and middleware via Laravel's auto-discovery. Add `@livewireScripts` to your layout if Livewire didn't auto-inject them.

Calling `render()` on a `BaseLivewireWidget` instance without Livewire installed will fail at the Blade compile step (the `<livewire:dynamic-component>` tag is unrecognised). The Layup package itself, including its config, migrations, commands, and any Blade widgets you register, continues to work normally regardless of whether Livewire is present.

## API reference

### `BaseLivewireWidget`

```php
namespace Crumbls\Layup\View;

abstract class BaseLivewireWidget extends BaseView implements Widget
{
    use HasWidgetDefaults;

    abstract public static function getType(): string;
    abstract public static function getLabel(): string;
    abstract public static function getLivewireComponent(): string;

    public static function getContentFormSchema(): array;
    public function render(): string;
}
```

`getLivewireComponent()` is the only Livewire-specific method. It returns either a Livewire alias (`'live-counter'`) or a fully-qualified class name (`\App\Livewire\LiveCounter::class`). Either form is accepted by `<livewire:dynamic-component>`.

`render()` is the only method that differs from `BaseBladeWidget`. It always returns a `string`. Override it only if you need a non-default `wire:key` strategy or want to add prop bindings beyond `data` and the default slot.

### `HasWidgetDefaults` trait

Holds the default implementations of every `Widget` interface method that is not widget-specific: `getIcon()`, `getCategory()`, `getDefaultData()`, `getPreview()`, `prepareForRender()`, `getValidationRules()`, `getSearchTerms()`, `isDeprecated()`, `getDeprecationMessage()`, `onSave()`, `onCreate()`, `onDelete()`, `onDuplicate()`, `getAssets()`, `toArray()`. Used by both `BaseBladeWidget` and `BaseLivewireWidget`. You will not normally use this trait directly -- it's an implementation detail of the bases.

### `BaseView::renderChildrenToHtml()`

```php
public function renderChildrenToHtml(): string
```

Recursively renders all of this view's children to a single HTML string. Used by `BaseLivewireWidget::render()` to produce the slot content. Available on every `BaseView` subclass, so you can call it from a custom render path if you build one.

### `Widget` interface vs. `BaseWidget` class

When you write code that inspects widgets at runtime (custom registries, content walkers, exporters), prefer `instanceof Widget` over `instanceof BaseWidget`. The interface matches both `BaseBladeWidget` subclasses and `BaseLivewireWidget` subclasses; the class only matches the Blade lineage. Layup's own internals were updated for this in the same release that introduced `BaseLivewireWidget`.
