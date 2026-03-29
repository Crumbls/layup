<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class BarCounterWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'bar-counter';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.bar-counter');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-chart-bar-square';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('bars')
                ->label(__('layup::widgets.bar-counter.bars'))
                ->schema([
                    TextInput::make('label')
                        ->label(__('layup::widgets.bar-counter.label'))
                        ->required(),
                    TextInput::make('percent')
                        ->label(__('layup::widgets.bar-counter.percentage'))
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->suffix('%')
                        ->required()
                        ->default(50),
                    ColorPicker::make('color')
                        ->label(__('layup::widgets.bar-counter.color')),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            Toggle::make('animate')
                ->label(__('layup::widgets.bar-counter.animate_on_scroll'))
                ->default(true),
            Toggle::make('show_percent')
                ->label(__('layup::widgets.bar-counter.show_percentage'))
                ->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'bars' => [
                ['label' => 'Skill 1', 'percent' => 80, 'color' => ''],
                ['label' => 'Skill 2', 'percent' => 65, 'color' => ''],
                ['label' => 'Skill 3', 'percent' => 90, 'color' => ''],
            ],
            'animate' => true,
            'show_percent' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['bars'] ?? []);

        return "▰▱ Bar Counters · {$count} bars";
    }
}
