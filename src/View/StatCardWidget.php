<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class StatCardWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'stat-card';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.stat-card');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-chart-bar';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('value')
                ->label(__('layup::widgets.stat-card.value'))
                ->placeholder(__('layup::widgets.stat-card.1_2m'))
                ->required(),
            TextInput::make('label')
                ->label(__('layup::widgets.stat-card.label'))
                ->placeholder(__('layup::widgets.stat-card.revenue'))
                ->required(),
            TextInput::make('description')
                ->label(__('layup::widgets.stat-card.description_change'))
                ->placeholder(__('layup::widgets.stat-card.12_from_last_month'))
                ->nullable(),
            Select::make('trend')
                ->label(__('layup::widgets.stat-card.trend'))
                ->options(['' => __('layup::widgets.stat-card.none'),
                    'up' => __('layup::widgets.stat-card.up_green'),
                    'down' => __('layup::widgets.stat-card.down_red'),
                    'neutral' => __('layup::widgets.stat-card.neutral_gray'), ])
                ->default('')
                ->nullable(),
            ColorPicker::make('accent_color')
                ->label(__('layup::widgets.stat-card.accent_color'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'value' => '',
            'label' => '',
            'description' => '',
            'trend' => '',
            'accent_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        return ($data['value'] ?? '') . ' ' . ($data['label'] ?? '');
    }
}
