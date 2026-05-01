<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Contracts\Widget;
use Crumbls\Layup\View\Concerns\HasWidgetDefaults;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

/**
 * Default Layup widget base. Renders via a Blade view component, resolved
 * from the type name (`layup::components.{type}`) unless the subclass
 * overrides getViewName().
 *
 * Pair with HasWidgetDefaults for the static metadata defaults. The render
 * return type is intentionally widened to View|Htmlable|string so the
 * call site (`{!! $widget->render() !!}`) accepts output from either base.
 */
abstract class BaseBladeWidget extends BaseView implements Widget
{
    use HasWidgetDefaults;

    abstract public static function getType(): string;

    abstract public static function getLabel(): string;

    /**
     * Widget-specific content fields. Every widget must override this.
     *
     * @return array<\Filament\Forms\Components\Component>
     */
    public static function getContentFormSchema(): array
    {
        return [];
    }

    /**
     * Get the view name for frontend rendering.
     * Convention: layup::components.{type}
     * Override for custom view paths.
     */
    protected function getViewName(): string
    {
        return 'layup::components.' . static::getType();
    }

    public function render(): View|Htmlable|string
    {
        return view($this->getViewName(), [
            'data' => $this->data,
            'children' => $this->children,
        ]);
    }
}
