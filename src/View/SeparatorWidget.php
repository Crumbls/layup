<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;

class SeparatorWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'separator';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.separator');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-minus';
    }

    public static function getCategory(): string
    {
        return 'layout';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Select::make('style')
                ->label(__('layup::widgets.separator.style'))
                ->options(['line' => __('layup::widgets.separator.simple_line'),
                    'dots' => __('layup::widgets.separator.dots'),
                    'stars' => __('layup::widgets.separator.stars'),
                    'diamond' => __('layup::widgets.separator.diamond'),
                    'wave' => __('layup::widgets.separator.wave'),
                    'fade' => __('layup::widgets.separator.fade_gradient'), ])
                ->default('line'),
            ColorPicker::make('color')
                ->label(__('layup::widgets.separator.color')),
            Select::make('width')
                ->label(__('layup::widgets.separator.width'))
                ->options(['25%' => __('layup::widgets.separator.25'),
                    '50%' => __('layup::widgets.separator.50'),
                    '75%' => __('layup::widgets.separator.75'),
                    '100%' => __('layup::widgets.separator.100'), ])
                ->default('50%'),
            Select::make('spacing')
                ->label(__('layup::widgets.separator.vertical_spacing'))
                ->options(['1rem' => __('layup::widgets.separator.small'),
                    '2rem' => __('layup::widgets.separator.medium'),
                    '3rem' => __('layup::widgets.separator.large'),
                    '4rem' => __('layup::widgets.separator.extra_large'), ])
                ->default('2rem'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'style' => 'line',
            'color' => null,
            'width' => '50%',
            'spacing' => '2rem',
        ];
    }

    public static function getPreview(array $data): string
    {
        return match ($data['style'] ?? 'line') {
            'dots' => '● ● ●',
            'stars' => '★ ★ ★',
            'diamond' => '◆',
            'wave' => '〰️ Wave',
            'fade' => '— Fade —',
            default => '── Line ──',
        };
    }
}
