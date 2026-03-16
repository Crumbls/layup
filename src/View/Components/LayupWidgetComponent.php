<?php

declare(strict_types=1);

namespace Crumbls\Layup\View\Components;

use Crumbls\Layup\Support\WidgetRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LayupWidgetComponent extends Component
{
    public function __construct(
        public string $type,
        public array $data = [],
    ) {}

    public function render(): View|string
    {
        $registry = app(WidgetRegistry::class);
        $class = $registry->get($this->type);

        if (! $class) {
            logger()->warning("Layup: Unknown widget type '{$this->type}' used in <x-layup-widget> component.");

            return '';
        }

        $widget = $class::make(array_merge($class::getDefaultData(), $this->data));

        return $widget->render();
    }
}
