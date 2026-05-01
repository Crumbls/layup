<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Contracts\Widget;
use Crumbls\Layup\View\Concerns\HasWidgetDefaults;
use Illuminate\Support\Facades\Blade;

/**
 * Layup widget base that renders through a Livewire component instead of
 * a Blade view component. The widget class itself is still a plain PHP
 * class implementing the Widget contract — it owns the editor experience
 * (form schema, defaults, lifecycle hooks, preview). Frontend output is
 * delegated to a separately-defined Livewire\Component referenced by name.
 *
 * Sibling of BaseBladeWidget. Both implement the same Widget contract,
 * both share static metadata defaults via HasWidgetDefaults, both can
 * register through the same WidgetRegistry. A downstream package can swap
 * a Blade-backed widget for a Livewire-backed one by registering a class
 * with the same getType().
 *
 * Children: pre-rendered to HTML on the server (recursing through
 * BaseView::renderChildrenToHtml()) and passed as the Livewire slot. The
 * Livewire component's view emits {{ $slot }} where children belong.
 * Children remain polymorphic — they may be Blade-rendered, Livewire-
 * rendered, or any mix.
 *
 * Note: livewire/livewire is a soft dependency. The package autoloads
 * cleanly without it, but instantiating a BaseLivewireWidget at render
 * time will fail unless Livewire is installed.
 */
abstract class BaseLivewireWidget extends BaseView implements Widget
{
    use HasWidgetDefaults;

    abstract public static function getType(): string;

    abstract public static function getLabel(): string;

    /**
     * Livewire component name (kebab-case alias) or fully-qualified class.
     */
    abstract public static function getLivewireComponent(): string;

    /**
     * Widget-specific content fields. Every widget must override this.
     *
     * @return array<\Filament\Forms\Components\Component>
     */
    public static function getContentFormSchema(): array
    {
        return [];
    }

    public function render(): string
    {
        $template = <<<'BLADE'
            <livewire:dynamic-component
                :component="$component"
                :wire:key="$key"
                :data="$data"
            >
                {!! $childrenHtml !!}
            </livewire:dynamic-component>
        BLADE;

        return Blade::render($template, [
            'component' => static::getLivewireComponent(),
            'key' => static::getType() . '-' . spl_object_id($this),
            'data' => $this->data,
            'childrenHtml' => $this->renderChildrenToHtml(),
        ]);
    }
}
