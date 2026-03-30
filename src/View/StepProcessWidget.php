<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class StepProcessWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'step-process';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.step-process');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-numbered-list';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('steps')
                ->label(__('layup::widgets.step-process.steps'))
                ->schema([
                    TextInput::make('title')
                        ->label(__('layup::widgets.step-process.title'))
                        ->required(),
                    TextInput::make('description')
                        ->label(__('layup::widgets.step-process.description'))
                        ->nullable(),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            Select::make('layout')
                ->label(__('layup::widgets.step-process.layout'))
                ->options(['vertical' => __('layup::widgets.step-process.vertical'),
                    'horizontal' => __('layup::widgets.step-process.horizontal'), ])
                ->default('horizontal'),
            ColorPicker::make('accent_color')
                ->label(__('layup::widgets.step-process.accent_color'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'steps' => [
                ['title' => 'Step 1', 'description' => 'Get started'],
                ['title' => 'Step 2', 'description' => 'Configure'],
                ['title' => 'Step 3', 'description' => 'Launch'],
            ],
            'layout' => 'horizontal',
            'accent_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['steps'] ?? []);

        return "1→2→3 Process ({$count} steps)";
    }
}
