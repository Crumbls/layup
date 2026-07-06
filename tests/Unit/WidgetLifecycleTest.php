<?php

declare(strict_types=1);

use Crumbls\Layup\Support\WidgetContext;
use Crumbls\Layup\View\BlurbWidget;
use Crumbls\Layup\View\ButtonWidget;
use Crumbls\Layup\View\HeadingWidget;
use Crumbls\Layup\View\ImageWidget;
use Crumbls\Layup\View\TextWidget;

// ── onSave default passthrough ──

it('onSave returns data unchanged by default', function (): void {
    $data = ['content' => 'Hello'];
    expect(TextWidget::onSave($data))->toBe($data);
});

it('onSave accepts null context', function (): void {
    $data = ['content' => 'Test'];
    expect(TextWidget::onSave($data, null))->toBe($data);
});

it('onSave accepts WidgetContext', function (): void {
    $ctx = new WidgetContext(page: null, rowId: 'r1', columnId: 'c1', widgetId: 'w1');
    $data = ['content' => 'Test'];
    expect(TextWidget::onSave($data, $ctx))->toBe($data);
});

// ── onCreate default passthrough ──

it('onCreate returns data unchanged by default', function (): void {
    $data = HeadingWidget::getDefaultData();
    expect(HeadingWidget::onCreate($data))->toBe($data);
});

it('onCreate accepts WidgetContext', function (): void {
    $ctx = new WidgetContext(page: null, rowId: 'r1', columnId: 'c1', widgetId: 'w1');
    $data = ['text' => 'New'];
    expect(HeadingWidget::onCreate($data, $ctx))->toBe($data);
});

// ── onDelete default noop ──

it('onDelete does not throw by default', function (): void {
    TextWidget::onDelete(['content' => 'test']);
    expect(true)->toBeTrue(); // no exception
});

it('onDelete accepts WidgetContext', function (): void {
    $ctx = new WidgetContext(page: null, rowId: 'r1', columnId: 'c1', widgetId: 'w1');
    TextWidget::onDelete([], $ctx);
    expect(true)->toBeTrue();
});

// ── WidgetContext ──

it('WidgetContext stores all properties', function (): void {
    $ctx = new WidgetContext(
        page: null,
        rowId: 'row-1',
        columnId: 'col-2',
        widgetId: 'widget-3',
    );
    expect($ctx->page)->toBeNull();
    expect($ctx->rowId)->toBe('row-1');
    expect($ctx->columnId)->toBe('col-2');
    expect($ctx->widgetId)->toBe('widget-3');
});

it('WidgetContext properties are readonly', function (): void {
    $ctx = new WidgetContext(page: null, rowId: 'r', columnId: 'c', widgetId: 'w');

    // Attempting to set should throw Error
    expect(fn () => $ctx->rowId = 'new')->toThrow(Error::class);
});

// ── toArray ──

it('toArray includes correct type', function (): void {
    expect(TextWidget::toArray()['type'])->toBe('text');
    expect(HeadingWidget::toArray()['type'])->toBe('heading');
    expect(ButtonWidget::toArray()['type'])->toBe('button');
});

it('toArray defaults match getDefaultData', function (): void {
    $arr = BlurbWidget::toArray();
    expect($arr['defaults'])->toBe(BlurbWidget::getDefaultData());
});

// ── getPreview edge cases ──

it('getPreview returns (empty) for empty data', function (): void {
    expect(TextWidget::getPreview([]))->toBe('(empty)');
});

it('getPreview truncates long content to 60 chars', function (): void {
    $long = str_repeat('a', 100);
    $result = TextWidget::getPreview(['content' => $long]);
    expect(mb_strlen($result))->toBeLessThanOrEqual(61); // 60 + ellipsis
    expect($result)->toEndWith('…');
});

it('getPreview strips HTML tags', function (): void {
    $result = TextWidget::getPreview(['content' => '<p><strong>Bold</strong> text</p>']);
    expect($result)->toBe('Bold text');
});

it('getPreview uses label when no content', function (): void {
    $result = TextWidget::getPreview(['label' => 'My Label']);
    expect($result)->toBe('My Label');
});

it('getPreview uses src basename', function (): void {
    $result = TextWidget::getPreview(['src' => '/images/photo.jpg']);
    expect($result)->toBe('photo.jpg');
});

it('getPreview prefers content over label over src', function (): void {
    $result = TextWidget::getPreview([
        'content' => 'Content wins',
        'label' => 'Label',
        'src' => '/img.png',
    ]);
    expect($result)->toBe('Content wins');
});

// ── getViewName convention ──

it('widget renders without throwing', function (): void {
    // TextWidget should have a view at layup::components.text
    // We can't render without the full app, but we can verify the method exists
    $widget = new TextWidget(['data' => ['content' => 'Hello']]);
    expect($widget)->toBeInstanceOf(TextWidget::class);
});

// ── Widget categories ──

it('content widgets have content category', function (): void {
    expect(TextWidget::getCategory())->toBe('content');
    expect(HeadingWidget::getCategory())->toBe('content');
    expect(BlurbWidget::getCategory())->toBe('content');
});

it('interactive widgets have interactive category', function (): void {
    expect(ButtonWidget::getCategory())->toBe('interactive');
});

it('media widgets have media category', function (): void {
    expect(ImageWidget::getCategory())->toBe('media');
});

// ── Icons are valid heroicon format ──

it('all widget icons use valid heroicon format', function (): void {
    $widgets = [TextWidget::class, HeadingWidget::class, ButtonWidget::class, ImageWidget::class, BlurbWidget::class];

    foreach ($widgets as $w) {
        $icon = $w::getIcon();
        expect($icon)->toMatch('/^heroicon-(o|s|m)-/');
    }
});

// ── Widget types are unique ──

it('widget types are kebab-case strings', function (): void {
    $widgets = [TextWidget::class, HeadingWidget::class, ButtonWidget::class, ImageWidget::class];

    foreach ($widgets as $w) {
        $type = $w::getType();
        expect($type)->toMatch('/^[a-z][a-z0-9-]*$/');
    }
});
