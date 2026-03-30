<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class FlipCardWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'flip-card';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.flip-card');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-arrows-up-down';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('front_title')
                ->label(__('layup::widgets.flip-card.front_title'))
                ->required(),
            TextInput::make('front_description')
                ->label(__('layup::widgets.flip-card.front_description'))
                ->nullable(),
            ColorPicker::make('front_bg')
                ->label(__('layup::widgets.flip-card.front_background'))
                ->default(null),
            TextInput::make('back_title')
                ->label(__('layup::widgets.flip-card.back_title'))
                ->required(),
            TextInput::make('back_description')
                ->label(__('layup::widgets.flip-card.back_description'))
                ->nullable(),
            ColorPicker::make('back_bg')
                ->label(__('layup::widgets.flip-card.back_background'))
                ->default(null),
            TextInput::make('link_url')
                ->label(__('layup::widgets.flip-card.link_url_back'))
                ->url()
                ->nullable(),
            TextInput::make('link_text')
                ->label(__('layup::widgets.flip-card.link_text'))
                ->default('Learn more'),
            Select::make('direction')
                ->label(__('layup::widgets.flip-card.flip_direction'))
                ->options(['horizontal' => __('layup::widgets.flip-card.horizontal'), 'vertical' => __('layup::widgets.flip-card.vertical')])
                ->default('horizontal'),
            Select::make('height')
                ->label(__('layup::widgets.flip-card.card_height'))
                ->options(['200px' => __('layup::widgets.flip-card.small'),
                    '300px' => __('layup::widgets.flip-card.medium'),
                    '400px' => __('layup::widgets.flip-card.large'), ])
                ->default('300px'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'front_title' => '',
            'front_description' => '',
            'front_bg' => null,
            'back_title' => '',
            'back_description' => '',
            'back_bg' => null,
            'link_url' => '',
            'link_text' => 'Learn more',
            'direction' => 'horizontal',
            'height' => '300px',
        ];
    }

    public static function getPreview(array $data): string
    {
        return '🔄 ' . ($data['front_title'] ?? '') . ' ↔ ' . ($data['back_title'] ?? '');
    }
}
