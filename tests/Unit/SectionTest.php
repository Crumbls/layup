<?php

declare(strict_types=1);

use Crumbls\Layup\View\Section;

it('has a form schema with 3 tabs', function (): void {
    $schema = Section::getFormSchema();
    expect($schema)->toHaveCount(1);
    expect($schema[0])->toBeInstanceOf(\Filament\Schemas\Components\Tabs::class);
});

it('builds section styles with background color', function (): void {
    $styles = Section::buildSectionStyles(['background_color' => '#ff0000']);
    expect($styles)->toContain('background-color: #ff0000');
});

it('builds section styles with gradient', function (): void {
    $styles = Section::buildSectionStyles(['background_gradient' => 'linear-gradient(135deg, #667eea, #764ba2)']);
    expect($styles)->toContain('linear-gradient');
});

it('builds section styles with background image', function (): void {
    $styles = Section::buildSectionStyles(['background_image' => 'test.jpg']);
    expect($styles)->toContain('background-image')
        ->and($styles)->toContain('background-size: cover');
});

it('adds parallax attachment for background image', function (): void {
    $styles = Section::buildSectionStyles(['background_image' => 'test.jpg', 'parallax' => true]);
    expect($styles)->toContain('background-attachment: fixed');
});

it('builds section styles with min height', function (): void {
    $styles = Section::buildSectionStyles(['min_height' => '100vh']);
    expect($styles)->toContain('min-height: 100vh');
});

it('returns empty string for empty settings', function (): void {
    expect(Section::buildSectionStyles([]))->toBe('');
});

it('includes shared design styles in section styles', function (): void {
    $styles = Section::buildSectionStyles([
        'text_color' => '#112233',
        'padding' => [
            'unit' => 'rem',
            'top' => 1,
            'right' => 2,
            'bottom' => 3,
            'left' => 4,
        ],
        'margin' => [
            'unit' => 'px',
            'top' => 5,
            'right' => 6,
            'bottom' => 7,
            'left' => 8,
        ],
    ]);

    expect($styles)
        ->toContain('color: #112233;')
        ->toContain('padding-top: 1rem;')
        ->toContain('margin-left: 8px');
});
