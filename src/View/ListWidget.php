<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ListWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'list';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.list');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-list-bullet';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('items')
                ->label(__('layup::widgets.list.list_items'))
                ->simple(
                    TextInput::make('text')->required()
                )
                ->defaultItems(3)
                ->columnSpanFull(),
            Select::make('style')
                ->label(__('layup::widgets.list.list_style'))
                ->options(['bullet' => __('layup::widgets.list.bullets'),
                    'number' => __('layup::widgets.list.1_numbered'),
                    'check' => __('layup::widgets.list.checkmarks'),
                    'arrow' => __('layup::widgets.list.arrows'),
                    'none' => __('layup::widgets.list.no_markers'), ])
                ->default('bullet'),
            ColorPicker::make('icon_color')
                ->label(__('layup::widgets.list.marker_color'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'items' => ['First item', 'Second item', 'Third item'],
            'style' => 'bullet',
            'icon_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['items'] ?? []);

        return "• List ({$count} items)";
    }
}
