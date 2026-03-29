<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class CallToActionWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'cta';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.cta');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-megaphone';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('layup::widgets.cta.title'))
                ->required(),
            RichEditor::make('content')
                ->label(__('layup::widgets.cta.body_text'))
                ->columnSpanFull(),
            TextInput::make('button_text')
                ->label(__('layup::widgets.cta.button_text'))
                ->default('Learn More'),
            TextInput::make('button_url')
                ->label(__('layup::widgets.cta.button_url'))
                ->url(),
            Select::make('button_style')
                ->label(__('layup::widgets.cta.button_style'))
                ->options(['primary' => __('layup::widgets.cta.primary'),
                    'secondary' => __('layup::widgets.cta.secondary'),
                    'outline' => __('layup::widgets.cta.outline'), ])
                ->default('primary'),
            Toggle::make('new_tab')
                ->label(__('layup::widgets.cta.open_in_new_tab'))
                ->default(false),
            ColorPicker::make('bg_color')
                ->label(__('layup::widgets.cta.background_color')),
            ColorPicker::make('text_color_cta')
                ->label(__('layup::widgets.cta.text_color')),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'title' => '',
            'content' => '',
            'button_text' => 'Learn More',
            'button_url' => '#',
        ];
    }

    public static function getPreview(array $data): string
    {
        $title = $data['title'] ?? '';

        return $title ? "📢 {$title}" : '(empty CTA)';
    }
}
