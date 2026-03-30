<?php

declare(strict_types=1);

use Crumbls\Layup\Forms\Components\ColorPicker;
use Crumbls\Layup\Support\LayupTheme;

// ── Instantiation ──

it('can be instantiated via make()', function (): void {
    $field = ColorPicker::make('color');

    expect($field)->toBeInstanceOf(ColorPicker::class);
});

// ── getSwatches ──

it('getSwatches() returns theme colors by default', function (): void {
    $theme = app(LayupTheme::class);
    $field = ColorPicker::make('color');

    expect($field->getSwatches())->toBe($theme->getColors());
});

it('getSwatches() returns custom swatches when set via swatches()', function (): void {
    $custom = [
        'Red' => '#ef4444',
        'Blue' => '#3b82f6',
    ];

    $field = ColorPicker::make('color')->swatches($custom);

    expect($field->getSwatches())->toBe($custom);
});

// ── getAllowCustom ──

it('getAllowCustom() defaults to true', function (): void {
    $field = ColorPicker::make('color');

    expect($field->getAllowCustom())->toBeTrue();
});

it('getAllowCustom() returns false when set via allowCustom(false)', function (): void {
    $field = ColorPicker::make('color')->allowCustom(false);

    expect($field->getAllowCustom())->toBeFalse();
});

// ── getContrastColor ──

it('getContrastColor() returns white for dark colors', function (): void {
    $field = ColorPicker::make('color');

    expect($field->getContrastColor('#1e3a5f'))->toBe('#ffffff');
    expect($field->getContrastColor('#000000'))->toBe('#ffffff');
});

it('getContrastColor() returns black for light colors', function (): void {
    $field = ColorPicker::make('color');

    expect($field->getContrastColor('#ffffff'))->toBe('#000000');
    expect($field->getContrastColor('#f0f8ff'))->toBe('#000000');
});

it('getContrastColor() handles 3-char hex shorthand', function (): void {
    $field = ColorPicker::make('color');

    // #fff expands to #ffffff — light, so contrast is black
    expect($field->getContrastColor('#fff'))->toBe('#000000');

    // #000 expands to #000000 — dark, so contrast is white
    expect($field->getContrastColor('#000'))->toBe('#ffffff');
});

// ── Nullable ──

it('is nullable by default (not required)', function (): void {
    $field = ColorPicker::make('color');

    expect($field->isRequired())->toBeFalse();
});
