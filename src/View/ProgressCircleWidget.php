<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ProgressCircleWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'progress-circle';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.progress-circle');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-arrow-path';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('percent')
                ->label(__('layup::widgets.progress-circle.percentage'))
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(75)
                ->required(),
            TextInput::make('title')
                ->label(__('layup::widgets.progress-circle.title'))
                ->nullable(),
            ColorPicker::make('color')
                ->label(__('layup::widgets.progress-circle.circle_color'))
                ->default(null),
            TextInput::make('size')
                ->label(__('layup::widgets.progress-circle.size_px'))
                ->numeric()
                ->default(120),
            TextInput::make('stroke_width')
                ->label(__('layup::widgets.progress-circle.stroke_width_px'))
                ->numeric()
                ->default(8),
            Toggle::make('animate')
                ->label(__('layup::widgets.progress-circle.animate_on_scroll'))
                ->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'percent' => 75,
            'title' => '',
            'color' => null,
            'size' => 120,
            'stroke_width' => 8,
            'animate' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        return "⭕ {$data['percent']}%";
    }
}
