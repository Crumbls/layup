<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;

class DividerWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'divider';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.divider');
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
                ->label(__('layup::widgets.divider.style'))
                ->options(['solid' => __('layup::widgets.divider.solid'),
                    'dashed' => __('layup::widgets.divider.dashed'),
                    'dotted' => __('layup::widgets.divider.dotted'),
                    'double' => __('layup::widgets.divider.double'), ])
                ->default('solid'),
            Select::make('weight')
                ->label(__('layup::widgets.divider.weight'))
                ->options(['1px' => __('layup::widgets.divider.thin_1px'),
                    '2px' => __('layup::widgets.divider.medium_2px'),
                    '3px' => __('layup::widgets.divider.thick_3px'),
                    '4px' => __('layup::widgets.divider.heavy_4px'), ])
                ->default('1px'),
            ColorPicker::make('color')
                ->label(__('layup::widgets.divider.color')),
            Select::make('width')
                ->label(__('layup::widgets.divider.width'))
                ->options(['100%' => __('layup::widgets.divider.full'),
                    '75%' => __('layup::widgets.divider.75'),
                    '50%' => __('layup::widgets.divider.50'),
                    '25%' => __('layup::widgets.divider.25'), ])
                ->default('100%'),
            Select::make('spacing')
                ->label(__('layup::widgets.divider.vertical_spacing'))
                ->options(['0.5rem' => __('layup::widgets.divider.compact'),
                    '1rem' => __('layup::widgets.divider.normal'),
                    '1.5rem' => __('layup::widgets.divider.relaxed'),
                    '2rem' => __('layup::widgets.divider.spacious'), ])
                ->default('1rem'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'style' => 'solid',
            'weight' => '1px',
            'color' => null,
            'width' => '100%',
            'spacing' => '1rem',
        ];
    }

    public static function getPreview(array $data): string
    {
        $style = $data['style'] ?? 'solid';
        $width = $data['width'] ?? '100%';

        return "— Divider · {$style} · {$width}";
    }
}
