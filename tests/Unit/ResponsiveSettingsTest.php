<?php

declare(strict_types=1);

use Crumbls\Layup\View\BaseView;
use Crumbls\Layup\View\Row;
use Crumbls\Layup\View\TextWidget;

it('renders responsive typography and spacing overrides with an isolated selector', function (): void {
    $data = ['font_size' => '1rem', 'responsive' => [
        'md' => ['font_size' => '2rem', 'padding' => ['unit' => 'rem', 'top' => 0, 'left' => 2]],
        'lg' => ['text_align' => 'right'],
    ]];
    $html = TextWidget::make($data)->render()->render();
    expect($html)->toContain('@media (min-width: 768px)', 'font-size: 2rem !important;', 'padding-top: 0rem !important;', 'padding-left: 2rem !important;', '--layup-style:', 'text-align: right !important;', 'data-layup-breakpoint="md"');
});

it('does not emit responsive markup for unset fields', function (): void {
    $data = ['responsive' => ['md' => ['font_size' => '', 'padding' => ['unit' => 'px', 'top' => null]]]];
    expect(BaseView::responsiveStyles($data))->toBe('');
    expect(BaseView::buildInlineStyles($data))->toBe('');
});

it('rejects injected CSS and ignores unknown breakpoints', function (): void {
    $data = ['responsive' => ['md' => ['font_size' => '2rem; } </style><script>alert(1)</script>'], 'unknown' => ['font_size' => '10rem']]];
    expect(BaseView::responsiveStyles($data))->toBe('');
});

it('exposes responsive design controls on rows columns sections and widgets', function (string $class): void {
    $paths = [];
    BaseView::walkComponents($class::getDesignFormSchema(), function ($component) use (&$paths): void {
        if (method_exists($component, 'getStatePath')) {
            try {
                $paths[] = $component->getStatePath(false);
            } catch (Throwable) {
            }
        }
    });
    expect($paths)->toContain('responsive.md', 'responsive.lg');
})->with([Row::class, \Crumbls\Layup\View\Column::class, \Crumbls\Layup\View\Section::class, TextWidget::class]);

it('keeps responsive opacity separate from entrance animation visibility', function (): void {
    $data = ['animation' => 'fade-in', 'responsive' => ['md' => ['opacity' => '0.5']]];
    expect(BaseView::responsiveStyles($data))->toContain('--layup-responsive-opacity: 0.5 !important;')
        ->not->toContain('{opacity: 0.5 !important;');
    expect(BaseView::animationAttributes($data))->toContain("opacity: 'var(--layup-responsive-opacity, 1)'", 'opacity: 0');
});
