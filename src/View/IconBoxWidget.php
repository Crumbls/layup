<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class IconBoxWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'icon-box';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.icon-box');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-square-3-stack-3d';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('icon')
                ->label(__('layup::widgets.icon-box.icon_emoji_or_text'))
                ->default('⚡')
                ->required(),
            TextInput::make('title')
                ->label(__('layup::widgets.icon-box.title'))
                ->required(),
            TextInput::make('description')
                ->label(__('layup::widgets.icon-box.description'))
                ->nullable(),
            TextInput::make('link_url')
                ->label(__('layup::widgets.icon-box.link_url'))
                ->url()
                ->nullable(),
            ColorPicker::make('icon_bg')
                ->label(__('layup::widgets.icon-box.icon_background_color')),
            ColorPicker::make('icon_color')
                ->label(__('layup::widgets.icon-box.icon_color'))
                ->default(null),
            Select::make('alignment')
                ->label(__('layup::widgets.icon-box.alignment'))
                ->options(['left' => __('layup::widgets.icon-box.left'), 'center' => __('layup::widgets.icon-box.center'), 'top' => __('layup::widgets.icon-box.top_icon_above')])
                ->default('top'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'icon' => '⚡',
            'title' => '',
            'description' => '',
            'link_url' => '',
            'icon_bg' => null,
            'icon_color' => null,
            'alignment' => 'top',
        ];
    }

    public static function getPreview(array $data): string
    {
        return ($data['icon'] ?? '⚡') . ' ' . ($data['title'] ?? '');
    }
}
