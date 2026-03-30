<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class NotificationBarWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'notification-bar';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.notification-bar');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-bell-alert';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('text')
                ->label(__('layup::widgets.notification-bar.message'))
                ->required()
                ->columnSpanFull(),
            TextInput::make('link_text')
                ->label(__('layup::widgets.notification-bar.link_text'))
                ->placeholder(__('layup::widgets.notification-bar.learn_more'))
                ->nullable(),
            TextInput::make('link_url')
                ->label(__('layup::widgets.notification-bar.link_url'))
                ->url()
                ->nullable(),
            ColorPicker::make('bg_color')
                ->label(__('layup::widgets.notification-bar.background_color'))
                ->default(null),
            ColorPicker::make('text_color_bar')
                ->label(__('layup::widgets.notification-bar.text_color'))
                ->default(null),
            Toggle::make('dismissible')
                ->label(__('layup::widgets.notification-bar.dismissible'))
                ->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'text' => '',
            'link_text' => '',
            'link_url' => '',
            'bg_color' => null,
            'text_color_bar' => null,
            'dismissible' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        $text = $data['text'] ?? '';

        return "🔔 {$text}";
    }
}
