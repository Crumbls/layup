<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class MenuWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'menu';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.menu');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-bars-3';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('items')
                ->label(__('layup::widgets.menu.menu_items'))
                ->schema([
                    TextInput::make('label')
                        ->label(__('layup::widgets.menu.label'))
                        ->required(),
                    TextInput::make('url')
                        ->label(__('layup::widgets.menu.url'))
                        ->required(),
                    Toggle::make('new_tab')
                        ->label(__('layup::widgets.menu.new_tab'))
                        ->default(false),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            Select::make('orientation')
                ->label(__('layup::widgets.menu.orientation'))
                ->options(['horizontal' => __('layup::widgets.menu.horizontal'),
                    'vertical' => __('layup::widgets.menu.vertical'), ])
                ->default('horizontal'),
            Select::make('style')
                ->label(__('layup::widgets.menu.style'))
                ->options(['links' => __('layup::widgets.menu.plain_links'),
                    'pills' => __('layup::widgets.menu.pills'),
                    'underline' => __('layup::widgets.menu.underline'), ])
                ->default('links'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'items' => [
                ['label' => 'Home', 'url' => '/', 'new_tab' => false],
                ['label' => 'About', 'url' => '/about', 'new_tab' => false],
                ['label' => 'Contact', 'url' => '/contact', 'new_tab' => false],
            ],
            'orientation' => 'horizontal',
            'style' => 'links',
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['items'] ?? []);

        return "☰ Menu ({$count} items)";
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
