<?php

declare(strict_types=1);

use Crumbls\Layup\Support\WidgetRegistry;
use Crumbls\Layup\View\ButtonWidget;
use Crumbls\Layup\View\Column;
use Crumbls\Layup\View\DividerWidget;
use Crumbls\Layup\View\HeadingWidget;
use Crumbls\Layup\View\HtmlWidget;
use Crumbls\Layup\View\Row;
use Crumbls\Layup\View\SpacerWidget;
use Crumbls\Layup\View\TextWidget;

beforeEach(function (): void {
    $registry = app(WidgetRegistry::class);
    $widgets = config('layup.widgets', []);
    foreach ($widgets as $class) {
        if (class_exists($class) && ! $registry->has($class::getType())) {
            $registry->register($class);
        }
    }
});

// --- Widget rendering ---

it('renders text widget without error', function (): void {
    $widget = TextWidget::make(['content' => '<p>Hello</p>']);
    $html = $widget->render()->toHtml();
    expect($html)->toContain('Hello');
});

it('renders heading widget with correct tag', function (): void {
    $widget = HeadingWidget::make(['content' => 'Title', 'level' => 'h3']);
    $html = $widget->render()->toHtml();
    expect($html)->toContain('<h3')
        ->and($html)->toContain('Title');
});

it('renders button widget with URL', function (): void {
    $widget = ButtonWidget::make(['label' => 'Click', 'url' => 'https://example.com', 'style' => 'primary', 'size' => 'md']);
    $html = $widget->render()->toHtml();
    expect($html)->toContain('Click')
        ->and($html)->toContain('https://example.com');
});

it('renders spacer widget with height', function (): void {
    $widget = SpacerWidget::make(['height' => '5rem']);
    $html = $widget->render()->toHtml();
    expect($html)->toContain('5rem');
});

it('renders divider widget with style', function (): void {
    $widget = DividerWidget::make(['style' => 'dotted', 'weight' => '3px', 'color' => '#999', 'width' => '100%', 'spacing' => '2rem']);
    $html = $widget->render()->toHtml();
    expect($html)->toContain('dotted');
});

it('renders html widget with raw content', function (): void {
    $widget = HtmlWidget::make(['content' => '<div class="custom">Raw HTML</div>']);
    $html = $widget->render()->toHtml();
    expect($html)->toContain('Raw HTML');
});

it('renders widget with custom id attribute', function (): void {
    $widget = TextWidget::make(['content' => 'test', 'id' => 'my-section']);
    $html = $widget->render()->toHtml();
    expect($html)->toContain('id="my-section"');
});

it('renders widget with custom class attribute', function (): void {
    $widget = TextWidget::make(['content' => 'test', 'class' => 'custom-class']);
    $html = $widget->render()->toHtml();
    expect($html)->toContain('custom-class');
});

// --- Row rendering ---

it('renders row with container class by default', function (): void {
    $col = Column::make(['span' => ['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]], []);
    $row = Row::make([], [$col]);
    $html = $row->render()->toHtml();
    expect($html)->toContain('container')
        ->and($html)->toContain('mx-auto')
        ->and($html)->toContain('flex flex-wrap');
});

it('renders full-width row without container', function (): void {
    $col = Column::make(['span' => ['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]], []);
    $row = Row::make(['full_width' => true], [$col]);
    $html = $row->render()->toHtml();
    expect($html)->toContain('flex flex-wrap');
    // The flex div should not have container class
    preg_match('/class="flex flex-wrap([^"]*)"/', $html, $matches);
    expect($matches[1] ?? '')->not->toContain('container');
});

it('renders row with background color', function (): void {
    $col = Column::make(['span' => ['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]], []);
    $row = Row::make(['background_color' => '#ff0000'], [$col]);
    $html = $row->render()->toHtml();
    expect($html)->toContain('background-color: #ff0000');
});

it('renders row with shared design and advanced settings', function (): void {
    $row = Row::make([
        'padding' => ['unit' => 'rem', 'top' => 1, 'right' => 2, 'bottom' => 3, 'left' => 4],
        'margin' => ['unit' => 'px', 'top' => 5, 'right' => 6, 'bottom' => 7, 'left' => 8],
        'hide_on' => ['sm'],
        'class' => 'custom-row',
        'animation' => 'fade-in',
    ]);

    $html = $row->render()->toHtml();

    expect($html)
        ->toContain('padding-top: 1rem;')
        ->toContain('margin-left: 8px;')
        ->toContain('hidden md:block')
        ->toContain('custom-row')
        ->toContain('x-intersect');
});

// --- Column rendering ---

it('renders column with correct responsive width classes', function (): void {
    $col = Column::make(['span' => ['sm' => 12, 'md' => 6, 'lg' => 4, 'xl' => 3]], []);
    $col->setPosition(first: true, last: true); // single column
    $html = $col->render()->toHtml();
    expect($html)->toContain('w-full')
        ->and($html)->toContain('md:w-6/12')
        ->and($html)->toContain('lg:w-4/12')
        ->and($html)->toContain('xl:w-3/12');
});

it('renders first column with pr gutter', function (): void {
    $col = Column::make(['span' => ['sm' => 12, 'md' => 6, 'lg' => 6, 'xl' => 6]], []);
    $col->setPosition(first: true, last: false);
    $html = $col->render()->toHtml();
    expect($html)->toContain('md:pr-2');
});

it('renders last column with pl gutter', function (): void {
    $col = Column::make(['span' => ['sm' => 12, 'md' => 6, 'lg' => 6, 'xl' => 6]], []);
    $col->setPosition(first: false, last: true);
    $html = $col->render()->toHtml();
    expect($html)->toContain('md:pl-2');
});

it('renders middle column with px gutter', function (): void {
    $col = Column::make(['span' => ['sm' => 12, 'md' => 4, 'lg' => 4, 'xl' => 4]], []);
    $col->setPosition(first: false, last: false);
    $html = $col->render()->toHtml();
    expect($html)->toContain('md:px-2');
});

it('renders only column with no gutter', function (): void {
    $col = Column::make(['span' => ['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]], []);
    $col->setPosition(first: true, last: true);
    $html = $col->render()->toHtml();
    expect($html)->not->toContain('md:pr-2')
        ->and($html)->not->toContain('md:pl-2')
        ->and($html)->not->toContain('md:px-2');
});

it('renders column with shared design and advanced settings', function (): void {
    $column = Column::make([
        'padding' => ['unit' => 'em', 'top' => 1, 'right' => 2, 'bottom' => 3, 'left' => 4],
        'margin' => ['unit' => '%', 'top' => 5, 'right' => 6, 'bottom' => 7, 'left' => 8],
        'hide_on' => ['lg'],
        'class' => 'custom-column',
        'animation' => 'slide-up',
    ]);

    $html = $column->render()->toHtml();

    expect($html)
        ->toContain('padding-bottom: 3em;')
        ->toContain('margin-right: 6%;')
        ->toContain('lg:hidden xl:block')
        ->toContain('custom-column')
        ->toContain('x-intersect');
});

// --- Visibility classes ---

it('generates correct visibility classes for hiding on mobile', function (): void {
    $classes = \Crumbls\Layup\View\BaseView::visibilityClasses(['sm']);
    expect($classes)->toBe('hidden md:block');
});

it('generates correct visibility classes for hiding on tablet', function (): void {
    $classes = \Crumbls\Layup\View\BaseView::visibilityClasses(['md']);
    expect($classes)->toBe('md:hidden lg:block');
});

it('generates correct visibility classes for hiding on mobile and tablet', function (): void {
    $classes = \Crumbls\Layup\View\BaseView::visibilityClasses(['sm', 'md']);
    expect($classes)->toBe('hidden lg:block');
});

it('returns empty string for no visibility restrictions', function (): void {
    expect(\Crumbls\Layup\View\BaseView::visibilityClasses([]))->toBe('');
});

it('builds inline styles with text color and alignment', function (): void {
    $styles = \Crumbls\Layup\View\BaseView::buildInlineStyles([
        'text_color' => '#ff0000',
        'text_align' => 'center',
        'background_color' => '#000',
    ]);
    expect($styles)->toContain('color: #ff0000;')
        ->and($styles)->toContain('text-align: center;')
        ->and($styles)->toContain('background-color: #000;');
});

it('renders text widget with hide_on classes', function (): void {
    $widget = TextWidget::make(['content' => 'hidden on mobile', 'hide_on' => ['sm']]);
    $html = $widget->render()->toHtml();
    expect($html)->toContain('hidden')
        ->and($html)->toContain('md:block');
});

it('renders text widget with text color', function (): void {
    $widget = TextWidget::make(['content' => 'colored', 'text_color' => '#ff0000']);
    $html = $widget->render()->toHtml();
    expect($html)->toContain('color: #ff0000');
});

// --- Animation attributes ---

it('generates animation attributes for fade-in', function (): void {
    $attrs = \Crumbls\Layup\View\BaseView::animationAttributes(['animation' => 'fade-in', 'animation_duration' => '500']);
    expect($attrs)->toContain('x-data')
        ->and($attrs)->toContain('x-intersect')
        ->and($attrs)->toContain('opacity: 0')
        ->and($attrs)->toContain('duration: 500');
});

it('returns empty string for no animation', function (): void {
    expect(\Crumbls\Layup\View\BaseView::animationAttributes([]))->toBe('');
    expect(\Crumbls\Layup\View\BaseView::animationAttributes(['animation' => '']))->toBe('');
});

it('generates slide-up animation attributes', function (): void {
    $attrs = \Crumbls\Layup\View\BaseView::animationAttributes(['animation' => 'slide-up']);
    expect($attrs)->toContain('translateY(2rem)');
});

it('builds inline styles with font size and border radius', function (): void {
    $styles = \Crumbls\Layup\View\BaseView::buildInlineStyles([
        'font_size' => '1.5rem',
        'border_radius' => '0.5rem',
    ]);
    expect($styles)->toContain('font-size: 1.5rem;')
        ->and($styles)->toContain('border-radius: 0.5rem;');
});

it('builds inline styles with border and box shadow', function (): void {
    $styles = \Crumbls\Layup\View\BaseView::buildInlineStyles([
        'border_width' => '2px',
        'border_style' => 'solid',
        'border_color' => '#333',
        'box_shadow' => '0 1px 2px 0 rgb(0 0 0 / 0.05)',
    ]);
    expect($styles)->toContain('border: 2px solid #333;')
        ->and($styles)->toContain('box-shadow:');
});

it('renders text widget with animation attributes', function (): void {
    $widget = TextWidget::make(['content' => 'animated', 'animation' => 'fade-in']);
    $html = $widget->render()->toHtml();
    expect($html)->toContain('x-intersect');
});

it('renders column with space-y-4 for vertical spacing', function (): void {
    $col = Column::make(['span' => ['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12]], []);
    $col->setPosition(first: true, last: true);
    $html = $col->render()->toHtml();
    expect($html)->toContain('space-y-4');
});
