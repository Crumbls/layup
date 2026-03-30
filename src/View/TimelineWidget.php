<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class TimelineWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'timeline';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.timeline');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-clock';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('events')
                ->label(__('layup::widgets.timeline.timeline_events'))
                ->schema([
                    TextInput::make('date')
                        ->label(__('layup::widgets.timeline.date_label'))
                        ->required(),
                    TextInput::make('title')
                        ->label(__('layup::widgets.timeline.title'))
                        ->required(),
                    TextInput::make('description')
                        ->label(__('layup::widgets.timeline.description'))
                        ->nullable(),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            ColorPicker::make('line_color')
                ->label(__('layup::widgets.timeline.line_color'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'events' => [
                ['date' => '2024', 'title' => 'Founded', 'description' => 'Started the journey.'],
                ['date' => '2025', 'title' => 'Growth', 'description' => 'Scaled to 1000 users.'],
                ['date' => '2026', 'title' => 'Today', 'description' => 'Serving customers worldwide.'],
            ],
            'line_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['events'] ?? []);

        return "📅 Timeline ({$count} events)";
    }
}
