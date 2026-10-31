<?php

declare(strict_types=1);

use Crumbls\Layup\Forms\Components\Traits\HandlesColumns;
use Crumbls\Layup\Forms\Components\Traits\HandlesRows;
use Crumbls\Layup\Forms\Components\Traits\HandlesSections;
use Crumbls\Layup\Forms\Components\Traits\HandlesWidgets;
use Crumbls\Layup\Support\ContentValidator;
use Crumbls\Layup\Support\WidgetRegistry;
use Crumbls\Layup\View\TextWidget;

class SectionBuilderHarness
{
    use HandlesColumns, HandlesRows, HandlesSections, HandlesWidgets;

    public function __construct(private array $content) {}

    public function getState(): array
    {
        return $this->content;
    }

    public function state(array $content): void
    {
        $this->content = $content;
    }
}

function sectionBuilderContent(): array
{
    return ['sections' => [
        ['id' => 's1', 'settings' => ['background_color' => '#fff'], 'rows' => [
            ['id' => 'r1', 'columns' => [['id' => 'c1', 'span' => ['sm' => 12], 'widgets' => [
                ['id' => 'w1', 'type' => 'text', 'data' => ['content' => 'Original']],
            ]]]],
        ]],
        ['id' => 's2', 'settings' => ['padding' => ['top' => 32]], 'rows' => []],
    ]];
}

beforeEach(function (): void {
    app(WidgetRegistry::class)->register(TextWidget::class);
});

it('validates sections and their nested rows', function (): void {
    $validator = new ContentValidator;
    expect($validator->validate(sectionBuilderContent())->passes())->toBeTrue();
    expect($validator->validate(['sections' => 'invalid'])->passes())->toBeFalse();
    expect($validator->validate(['sections' => [['rows' => [['columns' => 'invalid']]]]])->passes())->toBeFalse();
});

it('edits widgets and columns in sections without flattening or losing siblings', function (): void {
    $builder = new SectionBuilderHarness(sectionBuilderContent());
    $builder->widgetUpdateContent('r1', 'c1', 'w1', 'Edited');
    $builder->columnResize('r1', [['sm' => 6]]);
    $builder->widgetDuplicate('r1', 'c1', 'w1');
    $state = $builder->getState();
    expect($state)->not->toHaveKey('rows');
    expect($state['sections'][0]['rows'][0]['columns'][0]['widgets'])->toHaveCount(2);
    expect($state['sections'][0]['rows'][0]['columns'][0]['widgets'][0]['data']['content'])->toBe('Edited');
    expect($state['sections'][0]['rows'][0]['columns'][0]['span']['sm'])->toBe(6);
    expect($state['sections'][1])->toBe(sectionBuilderContent()['sections'][1]);
});

it('adds and duplicates rows inside their owning section', function (): void {
    $builder = new SectionBuilderHarness(sectionBuilderContent());
    $builder->rowAdd([6, 6], 0, 's2');
    $builder->rowDuplicate('r1');
    expect($builder->getState()['sections'][0]['rows'])->toHaveCount(2);
    expect($builder->getState()['sections'][1]['rows'])->toHaveCount(1);
});

it('adds a section while preserving legacy rows then duplicates and reorders it', function (): void {
    $rows = sectionBuilderContent()['sections'][0]['rows'];
    $builder = new SectionBuilderHarness(['rows' => $rows]);
    $builder->sectionAdd();
    $first = $builder->getState()['sections'][0];
    $builder->sectionDuplicate($first['id']);
    $clone = $builder->getState()['sections'][1];
    expect($clone['id'])->not->toBe($first['id']);
    expect($clone['rows'][0]['id'])->not->toBe('r1');
    expect($clone['rows'][0]['columns'][0]['widgets'][0]['id'])->not->toBe('w1');
    $builder->sectionMove($clone['id'], 'up');
    expect($builder->getState()['sections'][0]['id'])->toBe($clone['id']);
});

it('does not lose a moved widget when its destination no longer exists', function (): void {
    $builder = new SectionBuilderHarness(sectionBuilderContent());
    expect($builder->widgetMoveTo('r1', 'c1', 'w1', 'missing', 'missing', 0))->toBeFalse();
    expect($builder->getState())->toBe(sectionBuilderContent());
});
