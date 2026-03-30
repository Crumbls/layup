<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BackToTopWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'back-to-top';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.back-to-top');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-arrow-up';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            ColorPicker::make('bg_color')
                ->label(__('layup::widgets.back-to-top.background_color'))
                ->default(null),
            ColorPicker::make('text_color_btn')
                ->label(__('layup::widgets.back-to-top.icon_color'))
                ->default(null),
            Select::make('position')
                ->label(__('layup::widgets.back-to-top.position'))
                ->options(['right' => __('layup::widgets.back-to-top.bottom_right'),
                    'left' => __('layup::widgets.back-to-top.bottom_left'), ])
                ->default('right'),
            Select::make('size')
                ->label(__('layup::widgets.back-to-top.size'))
                ->options(['sm' => __('layup::widgets.back-to-top.small'), 'md' => __('layup::widgets.back-to-top.medium'), 'lg' => __('layup::widgets.back-to-top.large')])
                ->default('md'),
            TextInput::make('show_after')
                ->label(__('layup::widgets.back-to-top.show_after_scroll_px'))
                ->numeric()
                ->default(300),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'bg_color' => null,
            'text_color_btn' => null,
            'position' => 'right',
            'size' => 'md',
            'show_after' => 300,
        ];
    }

    public static function getPreview(array $data): string
    {
        return '⬆️ Back to Top button';
    }
}
