<?php

declare(strict_types=1);

namespace Crumbls\Layup\Forms\Components;

use Crumbls\Layup\Forms\Components\Traits\HandlesColumns;
use Crumbls\Layup\Forms\Components\Traits\HandlesRows;
use Crumbls\Layup\Forms\Components\Traits\HandlesWidgets;
use Crumbls\Layup\Support\WidgetRegistry;
use Filament\Forms\Components\Field;
use Filament\Support\Components\Attributes\ExposedLivewireMethod;
use Livewire\Attributes\Renderless;

class LayupBuilder extends Field
{
    use HandlesColumns;
    use HandlesRows;
    use HandlesWidgets;

    protected string $view = 'layup::forms.components.layup-builder';

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpanFull();

        $this->registerActions([
            $this->rowDeleteAction(),
            $this->rowEditAction(),
            $this->columnDeleteAction(),
            $this->columnEditAction(),
            $this->widgetEditAction(),
            $this->widgetDeleteAction(),
        ]);
    }

    public function getViewData(): array
    {
        $ret = parent::getViewData();
        $ret['breakpoints'] = $this->getBreakpointsProperty();
        $ret['defaultBreakpoint'] = $this->getDefaultBreakpointProperty();
        $ret['rowTemplates'] = $this->getRowTemplatesProperty();
        $ret['widgetRegistry'] = $this->getWidgetRegistryProperty();
        $ret['translations'] = $this->getTranslationsProperty();

        return $ret;
    }

    protected function syncContent(): void
    {
        // Intentionally empty — Alpine manages local state via $entangle.
        // Dispatch events directly from actions instead.
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function restoreContent(array $state = []): void
    {
        $this->state($state);
    }

    public function getWidgetRegistryProperty(): array
    {
        return app(WidgetRegistry::class)->toJs();
    }

    public function getBreakpointsProperty(): array
    {
        return config('layup.breakpoints', []);
    }

    public function getRowTemplatesProperty(): array
    {
        return config('layup.row_templates', []);
    }

    public function getDefaultBreakpointProperty(): string
    {
        return config('layup.default_breakpoint', 'lg');
    }

    public function getTranslationsProperty(): array
    {
        return [
            'saving' => __('layup::builder.saving'),
            'saved' => __('layup::builder.saved'),
            'row_label' => __('layup::builder.row_label'),
            'categories' => [
                'content' => __('layup::widgets.categories.content'),
                'media' => __('layup::widgets.categories.media'),
                'interactive' => __('layup::widgets.categories.interactive'),
                'layout' => __('layup::widgets.categories.layout'),
                'advanced' => __('layup::widgets.categories.advanced'),
            ],
        ];
    }
}
