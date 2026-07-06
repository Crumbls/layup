<?php

declare(strict_types=1);

use Crumbls\Layup\Contracts\Widget;
use Crumbls\Layup\Forms\Components\Traits\HandlesRows;
use Crumbls\Layup\Forms\Components\Traits\HandlesWidgets;
use Crumbls\Layup\Support\WidgetContext;
use Crumbls\Layup\Support\WidgetRegistry;
use Crumbls\Layup\View\BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

class DuplicateHookWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'duplicate-hook';
    }

    public static function getLabel(): string
    {
        return 'Duplicate Hook';
    }

    public static function onDuplicate(array $data, ?WidgetContext $context = null): array
    {
        $data['duplicated'] = ($data['duplicated'] ?? 0) + 1;
        $data['asset'] = ($data['asset'] ?? 'asset') . '-copy';

        return $data;
    }

    public function render(): View|Htmlable|string
    {
        return '';
    }
}

class BuilderDuplicateHarness
{
    use HandlesRows;
    use HandlesWidgets;

    public function __construct(private array $state) {}

    public function getState(): array
    {
        return $this->state;
    }

    public function state(array $state): void
    {
        $this->state = $state;
    }
}

beforeEach(function (): void {
    app(WidgetRegistry::class)->register(DuplicateHookWidget::class);
});

function duplicateHookState(): array
{
    return [
        'rows' => [[
            'id' => 'row-1',
            'columns' => [[
                'id' => 'col-1',
                'widgets' => [[
                    'id' => 'widget-1',
                    'type' => 'duplicate-hook',
                    'data' => ['asset' => 'hero.jpg'],
                ]],
            ]],
        ]],
    ];
}

it('fires the widget duplicate hook when duplicating a widget', function (): void {
    $builder = new BuilderDuplicateHarness(duplicateHookState());

    $clone = $builder->widgetDuplicate('row-1', 'col-1', 'widget-1');

    expect($clone['data'])
        ->toMatchArray([
            'asset' => 'hero.jpg-copy',
            'duplicated' => 1,
        ]);
});

it('fires the widget duplicate hook for widgets inside duplicated rows', function (): void {
    $builder = new BuilderDuplicateHarness(duplicateHookState());

    $clone = $builder->rowDuplicate('row-1');

    expect($clone['columns'][0]['widgets'][0]['data'])
        ->toMatchArray([
            'asset' => 'hero.jpg-copy',
            'duplicated' => 1,
        ]);
});
