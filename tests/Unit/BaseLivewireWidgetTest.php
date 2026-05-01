<?php

declare(strict_types=1);

use Crumbls\Layup\Contracts\Widget;
use Crumbls\Layup\Support\WidgetRegistry;
use Crumbls\Layup\View\BaseLivewireWidget;
use Crumbls\Layup\View\BaseView;
use Crumbls\Layup\View\BaseWidget;
use Livewire\Component as LivewireComponent;
use Livewire\Livewire;

/**
 * Fake Livewire-flavoured Layup widget used for exercising BaseLivewireWidget.
 * Has no children, declares its own form schema, points at the fake
 * Livewire component below for the render path.
 */
class FakeLivewireWidget extends BaseLivewireWidget
{
    public static function getType(): string
    {
        return 'fake-livewire-widget';
    }

    public static function getLabel(): string
    {
        return 'Fake Livewire Widget';
    }

    public static function getLivewireComponent(): string
    {
        return 'fake-livewire-widget';
    }

    public static function getDefaultData(): array
    {
        return ['message' => 'hello'];
    }

    public static function getPreview(array $data): string
    {
        return $data['message'] ?? '';
    }
}

/**
 * Real Livewire component the fake widget points at. Trivial render so we
 * can exercise Layup's Blade::render('<livewire:dynamic-component>') path
 * end-to-end without depending on any other view files.
 */
class FakeLivewireComponent extends LivewireComponent
{
    public array $data = [];

    public function mount(array $data = []): void
    {
        $this->data = $data;
    }

    public function render()
    {
        return <<<'BLADE'
            <div data-fake-livewire>{{ $data['message'] ?? '' }}{{ $slot ?? '' }}</div>
        BLADE;
    }
}

beforeEach(function (): void {
    Livewire::component('fake-livewire-widget', FakeLivewireComponent::class);
});

it('implements the Widget contract', function (): void {
    expect(is_subclass_of(FakeLivewireWidget::class, BaseLivewireWidget::class))->toBeTrue();
    expect((new ReflectionClass(FakeLivewireWidget::class))->implementsInterface(Widget::class))
        ->toBeTrue();
});

it('is recognised by instanceof Widget but not by instanceof BaseWidget', function (): void {
    $widget = FakeLivewireWidget::make(['message' => 'hi']);

    // The interface check is the load-bearing polymorphism for RegistersWidgets,
    // LayupContent, and LayupAssertions. It must accept Livewire widgets.
    expect($widget)->toBeInstanceOf(Widget::class);
    expect($widget)->toBeInstanceOf(BaseView::class);

    // BaseWidget is the Blade lineage. Livewire widgets must NOT match it -
    // they have a different render path, and code that key off of "is this a
    // Blade widget?" should reject them.
    expect($widget)->not->toBeInstanceOf(BaseWidget::class);
});

it('renders a Livewire-mounted output containing the configured component', function (): void {
    $widget = FakeLivewireWidget::make(['message' => 'rendered-payload']);

    $html = $widget->render();

    expect($html)->toBeString();
    // Livewire 4 wraps the mount with its own attributes; the rendered slot
    // content from FakeLivewireComponent must appear.
    expect($html)->toContain('data-fake-livewire');
    expect($html)->toContain('rendered-payload');
});

it('includes pre-rendered children HTML in the Livewire slot', function (): void {
    $child = FakeLivewireWidget::make(['message' => 'child-payload']);
    $parent = FakeLivewireWidget::make(['message' => 'parent-payload'], [$child]);

    $html = $parent->render();

    expect($html)->toContain('parent-payload');
    expect($html)->toContain('child-payload');
});

it('is registrable through WidgetRegistry alongside Blade widgets', function (): void {
    $registry = new WidgetRegistry;
    $registry->register(FakeLivewireWidget::class);

    expect($registry->has('fake-livewire-widget'))->toBeTrue();
    expect($registry->get('fake-livewire-widget'))->toBe(FakeLivewireWidget::class);
    expect($registry->getDefaultData('fake-livewire-widget'))
        ->toBe(['message' => 'hello']);
});

it('serializes through LayupContent like any other widget', function (): void {
    $registry = app(WidgetRegistry::class);
    $registry->register(FakeLivewireWidget::class);

    $content = new \Crumbls\Layup\Support\LayupContent([
        'sections' => [[
            'rows' => [[
                'columns' => [[
                    'span' => 12,
                    'widgets' => [[
                        'type' => 'fake-livewire-widget',
                        'data' => ['message' => 'serialized'],
                    ]],
                ]],
            ]],
        ]],
    ]);

    $array = $content->toArray();

    // toArray() returns rows; widget sits at row -> column -> widget.
    $widgetNode = $array[0]['children'][0]['children'][0];

    expect($widgetNode['type'])->toBe('fake-livewire-widget');
    expect($widgetNode['data'])->toBe(['message' => 'serialized']);
});

it('exposes HasWidgetDefaults defaults so toArray() includes the standard keys', function (): void {
    $payload = FakeLivewireWidget::toArray();

    expect($payload)->toHaveKeys([
        'type', 'label', 'icon', 'category', 'defaults',
        'search_terms', 'deprecated', 'deprecation_message',
    ]);

    // Defaults from HasWidgetDefaults that the fixture didn't override
    expect($payload['icon'])->toBe('heroicon-o-puzzle-piece');
    expect($payload['category'])->toBe('content');
    expect($payload['deprecated'])->toBeFalse();
});

it('passes lifecycle hooks through unchanged when not overridden', function (): void {
    $data = ['message' => 'unchanged'];

    expect(FakeLivewireWidget::onCreate($data))->toBe($data);
    expect(FakeLivewireWidget::onSave($data))->toBe($data);
    expect(FakeLivewireWidget::onDuplicate($data))->toBe($data);
    expect(FakeLivewireWidget::prepareForRender($data))->toBe($data);
});
