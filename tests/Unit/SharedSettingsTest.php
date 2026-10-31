<?php

declare(strict_types=1);

use Crumbls\Layup\Support\LayupContent;
use Crumbls\Layup\View\BaseView;
use Crumbls\Layup\View\Section;

it('applies shared design settings to sections', function (): void {
    $styles = Section::buildSectionStyles([
        'padding' => ['unit' => 'rem', 'top' => 0, 'right' => 2, 'bottom' => 3, 'left' => 4],
        'margin' => ['unit' => 'px', 'top' => -4],
        'border_radius' => '1rem', 'text_color' => '#123456', 'font_size' => '2rem',
        'box_shadow' => '0 1px 2px #000', 'opacity' => '0',
    ]);
    expect($styles)->toContain('padding-top: 0rem;', 'padding-right: 2rem;', 'margin-top: -4px;',
        'border-radius: 1rem;', 'color: #123456;', 'font-size: 2rem;', 'box-shadow: 0 1px 2px #000;', 'opacity: 0;');
});

it('renders saved legacy and simple padding values', function (mixed $padding, string $expected): void {
    expect(BaseView::buildInlineStyles(['padding' => $padding]))->toContain($expected);
})->with([[16, 'padding: 16px;'], ['p-4', 'padding: 1rem;'], ['2rem', 'padding: 2rem;']]);

it('renders row direction and wrapping settings', function (): void {
    $html = \Crumbls\Layup\View\Row::make(['direction' => 'column-reverse', 'wrap' => 'nowrap'])->render()->render();
    expect($html)->toContain('flex-direction: column-reverse;', 'flex-wrap: nowrap;');
});

it('preserves section settings through standalone content rendering', function (): void {
    $html = (new LayupContent(['sections' => [[
        'settings' => ['id' => 'hero-section', 'padding' => ['unit' => 'px', 'top' => 32]],
        'rows' => [['columns' => []]],
    ]]]))->toHtml();
    expect($html)->toContain('id="hero-section"', 'padding-top: 32px;');
});

it('applies shared settings to all visible built in widget wrappers', function (string $type): void {
    $registry = app(\Crumbls\Layup\Support\WidgetRegistry::class);
    foreach (config('layup.widgets') as $class) {
        $registry->register($class);
    }
    $class = $registry->get($type);
    $html = $class::make(array_merge($class::getDefaultData(), [
        'id' => 'shared-settings-test', 'class' => 'custom-test-class',
        'padding' => ['unit' => 'px', 'top' => 13], 'hide_on' => ['lg'],
    ]))->render()->render();
    expect($html)->toContain('id="shared-settings-test"', 'custom-test-class', 'padding-top: 13px;', 'lg:hidden');
})->with(['map', 'cookie-consent', 'back-to-top']);

it('supports an all sides spacing value with individual zero overrides', function (): void {
    expect(BaseView::buildInlineStyles(['padding' => ['all' => 2, 'left' => 0, 'unit' => 'rem']]))
        ->toContain('padding: 2rem;', 'padding-left: 0rem;');
    expect(BaseView::normalizeSettingsForForm(['padding' => 'p-4', 'margin' => 0]))
        ->toMatchArray(['padding' => ['all' => 1, 'unit' => 'rem'], 'margin' => ['all' => 0, 'unit' => 'px']]);
});

it('renders a section instance with its own settings and children', function (): void {
    $section = Section::make(['id' => 'standalone-section'], [\Crumbls\Layup\View\Row::make(['id' => 'child-row'])]);
    expect($section->render()->render())->toContain('id="standalone-section"', 'id="child-row"');
});

it('keeps custom section CSS after generated background and height styles', function (): void {
    $styles = Section::buildSectionStyles(['min_height' => '50vh', 'background_gradient' => 'linear-gradient(red, blue)', 'inline_css' => 'min-height: 10px; background: green;']);
    expect(strpos($styles, 'min-height: 10px'))->toBeGreaterThan(strpos($styles, 'min-height: 50vh'));
});

it('animates individual properties without replacing the saved padding and styles', function (): void {
    $attributes = BaseView::animationAttributes(['animation' => 'slide-up', 'opacity' => '0.5']);
    expect($attributes)->toContain('$el.animate', 'opacity: 0.5', "transform: 'translateY(0)'")
        ->not->toContain(':style=');
});

it('keeps hover and entrance animation bindings on separate button elements', function (): void {
    $data = array_merge(\Crumbls\Layup\View\ButtonWidget::getDefaultData(), ['animation' => 'fade-in', 'hover_bg_color' => '#000000']);
    $html = \Crumbls\Layup\View\ButtonWidget::make($data)->render()->render();
    preg_match('/<a\b[^>]*>/s', $html, $match);
    expect(substr_count($match[0], 'x-data='))->toBe(1);
    expect(substr_count($match[0], ':style='))->toBe(1);
    expect($html)->toContain('x-intersect.once', '$el.animate');
});

it('does not duplicate Alpine state or style bindings on interactive widget roots', function (string $type): void {
    $registry = app(\Crumbls\Layup\Support\WidgetRegistry::class);
    foreach (config('layup.widgets') as $class) {
        $registry->register($class);
    }
    $class = $registry->get($type);
    $html = $class::make(array_merge($class::getDefaultData(), ['animation' => 'fade-in']))->render()->render();
    $previous = libxml_use_internal_errors(true);
    $document = new DOMDocument;
    $document->loadHTML($html);
    $duplicates = array_filter(libxml_get_errors(), fn ($error): bool => str_contains($error->message, 'Attribute') && str_contains($error->message, 'redefined'));
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    expect($duplicates)->toBeEmpty();
    expect($html)->toContain('x-intersect.once');
})->with(['accordion', 'countdown', 'gallery', 'tabs', 'toggle', 'slider', 'number-counter', 'pricing-toggle', 'content-toggle', 'typewriter']);

it('preserves quoted custom CSS in button hover bindings', function (): void {
    $data = array_merge(\Crumbls\Layup\View\ButtonWidget::getDefaultData(), [
        'hover_bg_color' => '#000000', 'inline_css' => "font-family: 'Roboto', sans-serif;",
    ]);
    $html = \Crumbls\Layup\View\ButtonWidget::make($data)->render()->render();
    $previous = libxml_use_internal_errors(true);
    $document = new DOMDocument;
    $document->loadHTML($html);
    $anchor = $document->getElementsByTagName('a')->item(0);
    expect($anchor->getAttribute(':style'))->toContain('\\u0027Roboto\\u0027')->not->toContain("'Roboto'");
    expect($anchor->getAttribute('style'))->toContain("font-family: 'Roboto', sans-serif;");
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
});
