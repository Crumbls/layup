<?php

declare(strict_types=1);

use Crumbls\Layup\Support\LayupTheme;

// ── getDarkColors ──

it('getDarkColors() auto-lightens colors when no dark colors are set', function (): void {
    $theme = new LayupTheme;
    $theme->colors(['primary' => '#1e3a5f']);

    $dark = $theme->getDarkColors();

    expect($dark)->toHaveKey('primary');
    expect($dark['primary'])->toBeString()->toStartWith('#');

    // The lightened color should differ from the original
    expect($dark['primary'])->not->toBe('#1e3a5f');
});

it('getDarkColors() uses explicit dark colors when provided', function (): void {
    $theme = new LayupTheme;
    $theme->colors(['primary' => '#3b82f6']);
    $theme->darkColors(['primary' => '#93c5fd']);

    $dark = $theme->getDarkColors();

    expect($dark['primary'])->toBe('#93c5fd');
});

it('getDarkColors() merges explicit overrides with auto-lightened fallback', function (): void {
    $theme = new LayupTheme;
    $theme->colors([
        'primary' => '#3b82f6',
        'secondary' => '#6b7280',
    ]);
    $theme->darkColors(['primary' => '#93c5fd']);

    $dark = $theme->getDarkColors();

    // Explicitly provided key uses the given value
    expect($dark['primary'])->toBe('#93c5fd');

    // Non-overridden key uses auto-lightened value
    expect($dark['secondary'])->toBeString()->toStartWith('#');
    expect($dark['secondary'])->not->toBe('#6b7280');
});

// ── lightenForDark (tested via getDarkColors) ──

it('lightenForDark produces a lighter color (higher luminance)', function (): void {
    $theme = new LayupTheme;

    // Dark blue — low lightness
    $theme->colors(['primary' => '#1e3a5f']);
    $dark = $theme->getDarkColors();

    // Convert original and result to comparable luminance via a helper
    $originalL = hexToL('#1e3a5f');
    $lightenedL = hexToL($dark['primary']);

    expect($lightenedL)->toBeGreaterThan($originalL);
});

it('lightenForDark caps lightness at 85 percent', function (): void {
    $theme = new LayupTheme;

    // Very light color — already near max lightness
    $theme->colors(['primary' => '#f0f8ff']);
    $dark = $theme->getDarkColors();

    $lightenedL = hexToL($dark['primary']);

    // Should not exceed 0.85 lightness
    expect($lightenedL)->toBeLessThanOrEqual(0.86); // small float tolerance
});

it('lightenForDark handles 3-char hex shorthand', function (): void {
    $theme = new LayupTheme;
    $theme->colors(['primary' => '#369']);

    $dark = $theme->getDarkColors();

    expect($dark['primary'])->toBeString()->toStartWith('#');
    expect(strlen($dark['primary']))->toBe(7); // Always expands to full 6-char hex
});

// ── toCss ──

it('toCss() includes a .dark block with dark color values', function (): void {
    $theme = new LayupTheme;
    $theme->colors(['primary' => '#3b82f6']);

    $css = $theme->toCss();

    expect($css)->toContain('.dark {');
    expect($css)->toContain('--layup-primary:');
});

it('toCss() outputs both :root and .dark blocks', function (): void {
    $theme = new LayupTheme;
    $css = $theme->toCss();

    expect($css)->toContain(':root {');
    expect($css)->toContain('.dark {');
});

it('toCss() uses explicit dark color in .dark block', function (): void {
    $theme = new LayupTheme;
    $theme->colors(['primary' => '#3b82f6']);
    $theme->darkColors(['primary' => '#93c5fd']);

    $css = $theme->toCss();

    expect($css)->toContain('#93c5fd');
});

// ── darkColors() merging ──

it('darkColors() merges with existing dark colors', function (): void {
    $theme = new LayupTheme;
    $theme->darkColors(['primary' => '#93c5fd']);
    $theme->darkColors(['secondary' => '#d1d5db']);

    $dark = $theme->getDarkColors();

    // Both keys should be present
    expect($dark['primary'])->toBe('#93c5fd');
    expect($dark['secondary'])->toBe('#d1d5db');
});

// ── Helper ──

/**
 * Convert a hex color to HSL lightness (0–1) for assertion purposes.
 */
function hexToL(string $hex): float
{
    $hex = ltrim($hex, '#');

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    $r = hexdec(substr($hex, 0, 2)) / 255;
    $g = hexdec(substr($hex, 2, 2)) / 255;
    $b = hexdec(substr($hex, 4, 2)) / 255;

    $max = max($r, $g, $b);
    $min = min($r, $g, $b);

    return ($max + $min) / 2;
}
