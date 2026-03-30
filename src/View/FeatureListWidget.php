<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class FeatureListWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'feature-list';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.feature-list');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-check-circle';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('features')
                ->label(__('layup::widgets.feature-list.features'))
                ->schema([
                    TextInput::make('title')
                        ->label(__('layup::widgets.feature-list.title'))
                        ->required(),
                    TextInput::make('description')
                        ->label(__('layup::widgets.feature-list.description'))
                        ->nullable(),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            Select::make('icon_style')
                ->label(__('layup::widgets.feature-list.icon_style'))
                ->options(['check' => __('layup::widgets.feature-list.checkmark'),
                    'arrow' => __('layup::widgets.feature-list.arrow'),
                    'dot' => __('layup::widgets.feature-list.dot'),
                    'number' => __('layup::widgets.feature-list.1_numbered'), ])
                ->default('check'),
            ColorPicker::make('icon_color')
                ->label(__('layup::widgets.feature-list.icon_color'))
                ->default(null),
            Select::make('layout')
                ->label(__('layup::widgets.feature-list.layout'))
                ->options(['list' => __('layup::widgets.feature-list.vertical_list'),
                    'grid-2' => __('layup::widgets.feature-list.2_column_grid'),
                    'grid-3' => __('layup::widgets.feature-list.3_column_grid'), ])
                ->default('list'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'features' => [
                ['title' => 'Feature One', 'description' => 'A brief description of this feature.'],
                ['title' => 'Feature Two', 'description' => 'Another great feature explained.'],
                ['title' => 'Feature Three', 'description' => 'One more awesome capability.'],
            ],
            'icon_style' => 'check',
            'icon_color' => null,
            'layout' => 'list',
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['features'] ?? []);

        return "✓ Feature List ({$count} items)";
    }
}
