<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class TypewriterWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'typewriter';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.typewriter');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-cursor-arrow-rays';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('prefix')
                ->label(__('layup::widgets.typewriter.static_prefix'))
                ->placeholder(__('layup::widgets.typewriter.we_build'))
                ->nullable(),
            Repeater::make('words')
                ->label(__('layup::widgets.typewriter.rotating_words'))
                ->simple(
                    TextInput::make('word')
                        ->required()
                )
                ->defaultItems(3)
                ->columnSpanFull(),
            TextInput::make('suffix')
                ->label(__('layup::widgets.typewriter.static_suffix'))
                ->nullable(),
            TextInput::make('speed')
                ->label(__('layup::widgets.typewriter.typing_speed_ms_per_char'))
                ->numeric()
                ->default(100),
            TextInput::make('pause')
                ->label(__('layup::widgets.typewriter.pause_between_words_ms'))
                ->numeric()
                ->default(2000),
            Toggle::make('loop')
                ->label(__('layup::widgets.typewriter.loop'))
                ->default(true),
            ColorPicker::make('cursor_color')
                ->label(__('layup::widgets.typewriter.cursor_color'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'prefix' => '',
            'words' => ['amazing', 'beautiful', 'powerful'],
            'suffix' => '',
            'speed' => 100,
            'pause' => 2000,
            'loop' => true,
            'cursor_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        $first = is_array($data['words'] ?? null) ? ($data['words'][0] ?? '') : '';

        return "⌨️ {$data['prefix']}{$first}|";
    }
}
