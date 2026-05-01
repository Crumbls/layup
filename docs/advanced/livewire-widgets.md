---
title: Livewire-rendered widgets
weight: 4
---

By default, Layup widgets render through Blade view components. Frontend output for `HeadingWidget` resolves to `layup::components.heading`, the data array is passed as `$data`, and the result is plain HTML.

For widgets that need state, server actions, or reactive UI -- counters, contact forms, search boxes, anything stateful -- you can render through a Livewire component instead. The Widget contract is unchanged: form schema, defaults, lifecycle hooks, preview, and registration all work identically. Only the frontend render path differs.

## When to choose which

| Concern | Use `BaseWidget` (Blade) | Use `BaseLivewireWidget` |
|---|---|---|
| Static content (heading, image, text) | Yes | No |
| Server-rendered HTML, no interactivity | Yes | No |
| Form submissions, polling, server-side state | Possible via Alpine + endpoints | Yes -- this is what Livewire is for |
| Live counters, tabs with persistent state, search-as-you-type | Possible but awkward | Yes |
| Rows, columns, sections (structural) | Required | Not supported -- structural nodes recurse over child objects |

Container widgets (Section, Row, Column) must stay Blade-rendered because they recurse through `$children` as PHP objects. Leaf widgets are free to choose.

## The shape of a Livewire widget

A Livewire-rendered widget looks like a normal Layup widget plus a `getLivewireComponent()` method:

```php
<?php

declare(strict_types=1);

namespace App\Layup\Widgets;

use Crumbls\Layup\View\BaseLivewireWidget;
use Filament\Forms\Components\TextInput;

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

    public static function getLivewireComponent(): string
    {
        return 'live-counter';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('label')->required(),
            TextInput::make('start')->numeric()->default(0),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['label' => 'Clicks', 'start' => 0];
    }
}
```

The Livewire component itself is whatever you'd write normally. It receives the widget data as a `$data` prop and a `$slot` containing the pre-rendered children HTML:

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

    public function render()
    {
        return view('livewire.live-counter');
    }
}
```

```blade
{{-- resources/views/livewire/live-counter.blade.php --}}
<div>
    <p>{{ $data['label'] }}: {{ $count }}</p>
    <button wire:click="increment">+1</button>
    {{ $slot }}
</div>
```

Register the widget the same way as any other:

```php
// config/layup.php
'widgets' => [
    \App\Layup\Widgets\LiveCounterWidget::class,
    // ...
],
```

## Children inside a Livewire widget

`BaseLivewireWidget::render()` recursively renders the widget's children to a single HTML string (`renderChildrenToHtml()`) and passes the result as the Livewire slot. Inside the Livewire view, `{{ $slot }}` emits children wherever they belong.

Children remain polymorphic: a child of a Livewire widget can be a Blade widget, another Livewire widget, or any mix. Each child manages its own rendering independently.

When the parent Livewire component re-renders (in response to a `wire:click`, etc.), the slot content is preserved -- children are not re-executed on the server. This is the right default: structural data in children is part of the page, not the parent's state.

## Swapping a built-in widget for a Livewire flavour

Layup ships identity traits for several widgets so a downstream package can swap rendering without redeclaring the editor experience. For example:

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

Same `getType()` ("newsletter"), same form, same defaults, same preview. Different render path. Register against the same registry and the Livewire flavour overrides the Blade flavour:

```php
app(\Crumbls\Layup\Support\WidgetRegistry::class)
    ->register(\MyPackage\Widgets\NewsletterLivewireWidget::class);
```

`WidgetRegistry::register()` logs a warning when overriding an existing type so the swap is visible in logs.

## Identity traits

The traits that ship in `Crumbls\Layup\View\Concerns\Identity\` hold the static metadata for a widget -- type, label, icon, category, form schema, defaults, preview. They have no rendering logic. Using one in a Blade-rendered widget:

```php
class HeadingWidget extends BaseWidget
{
    use HeadingIdentity;
}
```

The same trait is what a Livewire flavour uses:

```php
class HeadingLivewireWidget extends BaseLivewireWidget
{
    use HeadingIdentity;

    public static function getLivewireComponent(): string
    {
        return 'layup-livewire.heading';
    }
}
```

Adding identity traits to your own widgets is optional -- it only matters if you intend to ship multiple render flavours of the same widget. For one-off widgets, define the static methods directly on the class.

## Installation note

`livewire/livewire` is a soft dependency. Layup loads cleanly without it. Install it only if you actually use `BaseLivewireWidget`:

```bash
composer require livewire/livewire
```

Calling `render()` on a `BaseLivewireWidget` instance without Livewire installed will fail at the Blade compile step.
