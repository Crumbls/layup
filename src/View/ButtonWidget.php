<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ButtonWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'button';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.button');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-cursor-arrow-rays';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('label')
                ->label(__('layup::widgets.button.button_text'))
                ->required()
                ->default('Click Me'),
            TextInput::make('url')
                ->label(__('layup::widgets.button.url'))
                ->url(),
            Select::make('style')
                ->label(__('layup::widgets.button.style'))
                ->options(['primary' => __('layup::widgets.button.primary'),
                    'secondary' => __('layup::widgets.button.secondary'),
                    'outline' => __('layup::widgets.button.outline'),
                    'ghost' => __('layup::widgets.button.ghost'), ])
                ->default('primary'),
            Select::make('size')
                ->label(__('layup::widgets.button.size'))
                ->options(['sm' => __('layup::widgets.button.small'),
                    'md' => __('layup::widgets.button.medium'),
                    'lg' => __('layup::widgets.button.large'), ])
                ->default('md'),
            Toggle::make('new_tab')
                ->label(__('layup::widgets.button.open_in_new_tab'))
                ->default(false),
            ColorPicker::make('bg_color')
                ->label(__('layup::widgets.button.custom_background_color')),
            ColorPicker::make('text_color_override')
                ->label(__('layup::widgets.button.custom_text_color')),
            ColorPicker::make('hover_bg_color')
                ->label(__('layup::widgets.button.hover_background_color')),
            ColorPicker::make('hover_text_color')
                ->label(__('layup::widgets.button.hover_text_color')),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'label' => 'Click Me',
            'url' => '#',
            'style' => 'primary',
            'size' => 'md',
            'new_tab' => false,
        ];
    }

    public static function getPreview(array $data): string
    {
        $label = $data['label'] ?? 'Button';
        $url = $data['url'] ?? '#';

        return "🔘 {$label}" . ($url !== '#' ? " → {$url}" : '');
    }
}
