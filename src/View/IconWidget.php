<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class IconWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'icon';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.icon');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-star';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('icon')
                ->label(__('layup::widgets.icon.icon_name'))
                ->required()
                ->placeholder(__('layup::widgets.icon.e_g_heroicon_o_heart')),
            ColorPicker::make('color')
                ->label(__('layup::widgets.icon.color')),
            Select::make('size')
                ->label(__('layup::widgets.icon.size'))
                ->options(['1.5rem' => __('layup::widgets.icon.small'),
                    '2.5rem' => __('layup::widgets.icon.medium'),
                    '4rem' => __('layup::widgets.icon.large'),
                    '6rem' => __('layup::widgets.icon.extra_large'),
                    '8rem' => __('layup::widgets.icon.huge'), ])
                ->default('2.5rem'),
            TextInput::make('url')
                ->label(__('layup::widgets.icon.link_url'))
                ->url()
                ->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'icon' => '',
            'color' => '',
            'size' => '2.5rem',
            'url' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        $icon = $data['icon'] ?? '';

        return $icon ? "✦ {$icon}" : '(no icon)';
    }
}
