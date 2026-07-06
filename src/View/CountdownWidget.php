<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class CountdownWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'countdown';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.countdown');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-clock';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('layup::widgets.countdown.title'))
                ->nullable(),
            DateTimePicker::make('target_date')
                ->label(__('layup::widgets.countdown.target_date'))
                ->required(),
            Toggle::make('show_days')
                ->label(__('layup::widgets.countdown.show_days'))
                ->default(true),
            Toggle::make('show_hours')
                ->label(__('layup::widgets.countdown.show_hours'))
                ->default(true),
            Toggle::make('show_minutes')
                ->label(__('layup::widgets.countdown.show_minutes'))
                ->default(true),
            Toggle::make('show_seconds')
                ->label(__('layup::widgets.countdown.show_seconds'))
                ->default(true),
            TextInput::make('expired_message')
                ->label(__('layup::widgets.countdown.expired_message'))
                ->default('Time is up!'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'title' => '',
            'target_date' => '',
            'show_days' => true,
            'show_hours' => true,
            'show_minutes' => true,
            'show_seconds' => true,
            'expired_message' => 'Time is up!',
        ];
    }

    public static function getPreview(array $data): string
    {
        $date = $data['target_date'] ?? '';

        return $date ? "Countdown to {$date}" : '(no date set)';
    }
}
