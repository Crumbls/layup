<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

class TabsWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'tabs';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.tabs');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-rectangle-stack';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('tabs')
                ->label(__('layup::widgets.tabs.tabs'))
                ->schema([
                    TextInput::make('title')
                        ->label(__('layup::widgets.tabs.tab_title'))
                        ->required(),
                    RichEditor::make('content')
                        ->label(__('layup::widgets.tabs.content'))
                        ->columnSpanFull(),
                ])
                ->defaultItems(2)
                ->collapsible()
                ->columnSpanFull(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'tabs' => [
                ['title' => 'Tab 1', 'content' => ''],
                ['title' => 'Tab 2', 'content' => ''],
            ],
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['tabs'] ?? []);

        return "Tabs · {$count} tabs";
    }
}
