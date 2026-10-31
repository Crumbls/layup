<?php

declare(strict_types=1);

use Crumbls\Layup\View\Column;
use Crumbls\Layup\View\Row;

// ── visibilityClasses() ──

it('visibilityClasses returns empty for no hidden breakpoints', function (): void {
    expect(Row::visibilityClasses([]))->toBe('');
});

it('visibilityClasses hides on mobile only', function (): void {
    $result = Row::visibilityClasses(['sm']);
    expect($result)->toContain('hidden');
    expect($result)->toContain('md:block');
});

it('visibilityClasses hides on desktop only', function (): void {
    $result = Row::visibilityClasses(['lg']);
    expect($result)->toContain('lg:hidden');
    expect($result)->toContain('xl:block');
});

it('visibilityClasses hides on multiple breakpoints', function (): void {
    $result = Row::visibilityClasses(['sm', 'md']);
    expect($result)->toContain('hidden');
    expect($result)->toContain('lg:block');
});

it('visibilityClasses hides on all breakpoints', function (): void {
    $result = Row::visibilityClasses(['sm', 'md', 'lg', 'xl']);
    expect($result)->toContain('hidden');
    expect($result)->not->toContain('block');
});

it('visibilityClasses handles non-contiguous breakpoints', function (): void {
    $result = Row::visibilityClasses(['sm', 'lg']);
    expect($result)->toContain('hidden');
    expect($result)->toContain('md:block');
    expect($result)->toContain('lg:hidden');
    expect($result)->toContain('xl:block');
});

// ── buildInlineStyles() ──

it('buildInlineStyles returns empty for empty data', function (): void {
    expect(Row::buildInlineStyles([]))->toBe('');
});

it('buildInlineStyles includes text color', function (): void {
    $result = Row::buildInlineStyles(['text_color' => '#ff0000']);
    expect($result)->toContain('color: #ff0000;');
});

it('buildInlineStyles includes text alignment', function (): void {
    $result = Row::buildInlineStyles(['text_align' => 'center']);
    expect($result)->toContain('text-align: center;');
});

it('buildInlineStyles includes font size', function (): void {
    $result = Row::buildInlineStyles(['font_size' => '1.5rem']);
    expect($result)->toContain('font-size: 1.5rem;');
});

it('buildInlineStyles includes border radius', function (): void {
    $result = Row::buildInlineStyles(['border_radius' => '0.5rem']);
    expect($result)->toContain('border-radius: 0.5rem;');
});

it('buildInlineStyles includes full border when width + style set', function (): void {
    $result = Row::buildInlineStyles([
        'border_width' => '2px',
        'border_style' => 'solid',
        'border_color' => '#333',
    ]);
    expect($result)->toContain('border: 2px solid #333;');
});

it('buildInlineStyles uses default border color when only width + style set', function (): void {
    $result = Row::buildInlineStyles([
        'border_width' => '1px',
        'border_style' => 'dashed',
    ]);
    expect($result)->toContain('border: 1px dashed #e5e7eb;');
});

it('buildInlineStyles skips border when only width set without style', function (): void {
    $result = Row::buildInlineStyles(['border_width' => '1px']);
    expect($result)->not->toContain('border:');
});

it('buildInlineStyles includes box shadow', function (): void {
    $shadow = '0 1px 2px 0 rgb(0 0 0 / 0.05)';
    $result = Row::buildInlineStyles(['box_shadow' => $shadow]);
    expect($result)->toContain("box-shadow: {$shadow};");
});

it('buildInlineStyles includes opacity', function (): void {
    $result = Row::buildInlineStyles(['opacity' => '0.5']);
    expect($result)->toContain('opacity: 0.5;');
});

it('buildInlineStyles includes background color', function (): void {
    $result = Row::buildInlineStyles(['background_color' => '#f0f0f0']);
    expect($result)->toContain('background-color: #f0f0f0;');
});

it('buildInlineStyles appends inline CSS', function (): void {
    $result = Row::buildInlineStyles(['inline_css' => 'transform: rotate(5deg);']);
    expect($result)->toContain('transform: rotate(5deg);');
});

it('buildInlineStyles includes per-side padding and margin', function (): void {
    $result = Row::buildInlineStyles([
        'padding' => [
            'unit' => 'rem',
            'top' => 1,
            'right' => 2,
            'bottom' => 3,
            'left' => 4,
        ],
        'margin' => [
            'unit' => '%',
            'top' => 5,
            'right' => 6,
            'bottom' => 7,
            'left' => 8,
        ],
    ]);

    expect($result)
        ->toContain('padding-top: 1rem;')
        ->toContain('padding-right: 2rem;')
        ->toContain('padding-bottom: 3rem;')
        ->toContain('padding-left: 4rem;')
        ->toContain('margin-top: 5%;')
        ->toContain('margin-right: 6%;')
        ->toContain('margin-bottom: 7%;')
        ->toContain('margin-left: 8%;');
});

it('buildInlineStyles preserves zero spacing values and ignores invalid values', function (): void {
    $result = Row::buildInlineStyles([
        'padding' => [
            'unit' => 'invalid',
            'top' => 0,
            'right' => '0',
            'bottom' => null,
            'left' => 'not-numeric',
        ],
    ]);

    expect($result)
        ->toContain('padding-top: 0px;')
        ->toContain('padding-right: 0px;')
        ->not->toContain('padding-bottom:')
        ->not->toContain('padding-left:');
});

it('buildInlineStyles combines multiple properties', function (): void {
    $result = Row::buildInlineStyles([
        'text_color' => '#000',
        'text_align' => 'right',
        'background_color' => '#fff',
    ]);
    expect($result)->toContain('color: #000;');
    expect($result)->toContain('text-align: right;');
    expect($result)->toContain('background-color: #fff;');
});

it('buildInlineStyles ignores empty string values', function (): void {
    $result = Row::buildInlineStyles([
        'text_color' => '',
        'text_align' => '',
        'opacity' => '',
    ]);
    expect($result)->toBe('');
});

// ── animationAttributes() ──

it('animationAttributes returns empty for no animation', function (): void {
    expect(Row::animationAttributes([]))->toBe('');
    expect(Row::animationAttributes(['animation' => '']))->toBe('');
});

it('animationAttributes returns Alpine directives for fade-in', function (): void {
    $result = Row::animationAttributes(['animation' => 'fade-in']);
    expect($result)->toContain('x-data');
    expect($result)->toContain('x-intersect');
    expect($result)->toContain('$el.animate');
    expect($result)->toContain('opacity: 0');
    expect($result)->toContain('opacity: 1');
});

it('animationAttributes can reuse an existing Alpine scope', function (): void {
    $result = Row::animationAttributes(
        ['animation' => 'fade-in'],
        hasAlpineData: true,
    );

    expect($result)
        ->not->toContain('x-data')
        ->toContain('x-intersect.once')
        ->toContain('$el.animate');
});

it('animationExpression can be combined with an existing intersection handler', function (): void {
    $result = Row::animationExpression([
        'animation' => 'slide-up',
        'animation_duration' => '700',
    ]);

    expect($result)
        ->toContain('$el.animate')
        ->toContain('translateY(2rem)')
        ->toContain('duration: 700');
});

it('animationAttributes uses custom duration', function (): void {
    $result = Row::animationAttributes([
        'animation' => 'slide-up',
        'animation_duration' => '1000',
    ]);
    expect($result)->toContain('duration: 1000');
    expect($result)->toContain('translateY');
});

it('animationAttributes defaults to 500ms duration', function (): void {
    $result = Row::animationAttributes(['animation' => 'zoom-in']);
    expect($result)->toContain('duration: 500');
});

it('animationAttributes handles all animation types', function (): void {
    $types = ['fade-in', 'slide-up', 'slide-down', 'slide-left', 'slide-right', 'zoom-in'];

    foreach ($types as $type) {
        $result = Row::animationAttributes(['animation' => $type]);
        expect($result)->not->toBeEmpty("Animation type '{$type}' should produce attributes");
    }
});

it('animationAttributes returns empty for unknown animation type', function (): void {
    $result = Row::animationAttributes(['animation' => 'unknown-type']);
    expect($result)->toBe('');
});

// ── setPosition / isFirst / isLast ──

it('setPosition sets first and last flags', function (): void {
    $row = Row::make();
    expect($row->isFirst())->toBeFalse();
    expect($row->isLast())->toBeFalse();

    $row->setPosition(first: true, last: true);
    expect($row->isFirst())->toBeTrue();
    expect($row->isLast())->toBeTrue();
});

it('setPosition returns self for fluent chaining', function (): void {
    $row = Row::make();
    $result = $row->setPosition(first: true);
    expect($result)->toBe($row);
});

// ── make() with data and children ──

it('make() accepts data and children', function (): void {
    $child = Column::make(['span' => ['lg' => 6]]);
    $row = Row::make(['gap' => 'gap-4'], [$child]);

    expect($row->getData())->toBe(['gap' => 'gap-4']);
    expect($row->getChildren())->toHaveCount(1);
    expect($row->getChildren()[0])->toBe($child);
});

// ── setChildren replaces completely ──

it('setChildren replaces existing children', function (): void {
    $row = Row::make();
    $row->addChild(Column::make());
    $row->addChild(Column::make());
    expect($row->getChildren())->toHaveCount(2);

    $row->setChildren([Column::make()]);
    expect($row->getChildren())->toHaveCount(1);
});

// ── setChildren returns self ──

it('setChildren returns self for fluent chaining', function (): void {
    $row = Row::make();
    $result = $row->setChildren([]);
    expect($result)->toBe($row);
});

// ── addChild returns self ──

it('addChild returns self for fluent chaining', function (): void {
    $row = Row::make();
    $result = $row->addChild(Column::make());
    expect($result)->toBe($row);
});
