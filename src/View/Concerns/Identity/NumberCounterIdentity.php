<?php

declare(strict_types=1);

namespace Crumbls\Layup\View\Concerns\Identity;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

trait NumberCounterIdentity
{
    public static function getType(): string
    {
        return 'number-counter';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.number-counter');
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
            TextInput::make('number')
                ->label(__('layup::widgets.number-counter.number'))
                ->numeric()
                ->required()
                ->default(100),
            TextInput::make('prefix')
                ->label(__('layup::widgets.number-counter.prefix'))
                ->placeholder(__('layup::widgets.number-counter.e_g'))
                ->nullable(),
            TextInput::make('suffix')
                ->label(__('layup::widgets.number-counter.suffix'))
                ->placeholder(__('layup::widgets.number-counter.e_g_or'))
                ->nullable(),
            TextInput::make('title')
                ->label(__('layup::widgets.number-counter.title'))
                ->nullable(),
            Toggle::make('animate')
                ->label(__('layup::widgets.number-counter.animate_on_scroll'))
                ->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'number' => 100,
            'prefix' => '',
            'suffix' => '',
            'title' => '',
            'animate' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        $prefix = $data['prefix'] ?? '';
        $number = $data['number'] ?? 0;
        $suffix = $data['suffix'] ?? '';
        $title = $data['title'] ?? '';

        return "{$prefix}{$number}{$suffix}" . ($title ? " — {$title}" : '');
    }
}
