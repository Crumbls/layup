<?php

declare(strict_types=1);

use Crumbls\Layup\Support\SafelistCollector;
use Crumbls\Layup\View\Column;
use Crumbls\Layup\View\Row;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Component;

it('falls back to flex for legacy and unrecognized layouts', function ($layout): void {
    $html = Row::make(['layout' => $layout], [Column::make()->span(6)])->render()->toHtml();

    expect($html)->toContain('flex flex-wrap', 'w-6/12')->not->toContain('grid-cols-12', 'col-span-6');
})->with([null, '', 'unknown', 'flex']);

it('renders grid spans and gaps without flex widths or gutters', function (): void {
    $column = Column::make()->span(['sm' => 12, 'md' => 6, 'lg' => 4, 'xl' => 3]);
    $html = Row::make(['layout' => 'grid', 'gap' => 'gap-8'], [$column, Column::make()->span(6)])->render()->toHtml();

    expect($html)->toContain('grid grid-cols-12 gap-8', 'col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-3')
        ->not->toContain('md:w-', 'md:pr-2', 'md:pl-2', 'flex-direction:');

    expect(Row::make([], [$column])->render()->toHtml())->toContain('md:w-6/12')->not->toContain('col-span-');
});

it('supports every grid gap and safelists every responsive span', function (): void {
    $classes = SafelistCollector::staticClasses();
    foreach (Row::GAPS as $gap) {
        expect(Row::make(['layout' => 'grid', 'gap' => $gap])->render()->toHtml())->toContain('grid grid-cols-12 ' . $gap);
        expect($classes)->toContain($gap);
    }
    foreach (['', 'md:', 'lg:', 'xl:'] as $prefix) {
        foreach (range(1, 12) as $span) {
            expect($classes)->toContain($prefix . 'col-span-' . $span);
        }
    }
    expect(Row::make(['layout' => 'grid', 'gap' => 'invalid'])->render()->toHtml())->toContain('grid-cols-12 gap-4');
});

it('honors flex direction and wrapping', function (): void {
    $html = Row::make(['direction' => 'row-reverse', 'wrap' => 'nowrap'])->render()->toHtml();
    expect($html)->toContain('flex-direction: row-reverse;', 'flex-wrap: nowrap;');
});

it('shows only applicable controls and defaults to flex', function (): void {
    $component = new class extends Component implements HasSchemas
    {
        use InteractsWithSchemas;

        public array $data = [];
    };
    $schema = Schema::make($component)->statePath('data')->components(Row::getContentFormSchema());
    $schema->fill();
    expect($component->data['layout'])->toBe('flex');
    $fields = collect($schema->getComponents(withHidden: true))->keyBy(fn ($field) => $field->getName());
    expect($fields['gap']->isVisible())->toBeFalse();
    foreach (['direction', 'wrap', 'justify'] as $name) {
        expect($fields[$name]->isVisible())->toBeTrue();
    }
    $component->data['layout'] = 'grid';
    expect($fields['gap']->isVisible())->toBeTrue();
    foreach (['direction', 'wrap', 'justify'] as $name) {
        expect($fields[$name]->isVisible())->toBeFalse();
    }
    expect($fields['align']->isVisible())->toBeTrue();
});

it('keeps the layout selector immediately reactive in the full row menu', function (): void {
    $component = new class extends Component implements HasSchemas
    {
        use InteractsWithSchemas;

        public array $data = [];
    };
    $schema = Schema::make($component)->statePath('data')->components(Row::getFormSchema());
    $schema->fill();
    $tabs = $schema->getComponents()[0]->getChildSchema()->getComponents();
    $fields = collect($tabs[0]->getChildSchema()->getComponents(withHidden: true))->keyBy(fn ($field) => $field->getName());

    expect($fields['layout']->isLive())->toBeTrue()
        ->and($fields['layout']->isLiveOnBlur())->toBeFalse()
        ->and($fields['gap']->isLiveOnBlur())->toBeTrue();
});
