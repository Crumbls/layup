<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class GradientTextWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'gradient-text';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.gradient-text');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-paint-brush';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('text')
                ->label(__('layup::widgets.gradient-text.text'))
                ->required()
                ->columnSpanFull(),
            Select::make('tag')
                ->label(__('layup::widgets.gradient-text.html_tag'))
                ->options(['h1' => __('layup::widgets.gradient-text.h1'), 'h2' => __('layup::widgets.gradient-text.h2'), 'h3' => __('layup::widgets.gradient-text.h3'), 'h4' => __('layup::widgets.gradient-text.h4'), 'p' => __('layup::widgets.gradient-text.paragraph'), 'span' => __('layup::widgets.gradient-text.span')])
                ->default('h2'),
            ColorPicker::make('from_color')
                ->label(__('layup::widgets.gradient-text.from_color')),
            ColorPicker::make('to_color')
                ->label(__('layup::widgets.gradient-text.to_color')),
            ColorPicker::make('via_color')
                ->label(__('layup::widgets.gradient-text.via_color_optional')),
            Select::make('direction')
                ->label(__('layup::widgets.gradient-text.direction'))
                ->options(['to right' => __('layup::widgets.gradient-text.left_right'),
                    'to left' => __('layup::widgets.gradient-text.right_left'),
                    'to bottom' => __('layup::widgets.gradient-text.top_bottom'),
                    'to bottom right' => __('layup::widgets.gradient-text.diagonal'),
                    '135deg' => __('layup::widgets.gradient-text.135deg_diagonal'), ])
                ->default('to right'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'text' => '',
            'tag' => 'h2',
            'from_color' => null,
            'to_color' => null,
            'via_color' => '',
            'direction' => 'to right',
        ];
    }

    public static function getPreview(array $data): string
    {
        $text = $data['text'] ?? '';

        return "🌈 {$text}";
    }
}
