<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class FeatureGridWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'feature-grid';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.feature-grid');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-squares-plus';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('features')
                ->label(__('layup::widgets.feature-grid.features'))
                ->schema([
                    TextInput::make('icon')->label(__('layup::widgets.feature-grid.icon'))->nullable(),
                    TextInput::make('title')->label(__('layup::widgets.feature-grid.title'))->required(),
                    TextInput::make('description')->label(__('layup::widgets.feature-grid.description'))->nullable(),
                ])
                ->defaultItems(6)
                ->columnSpanFull(),
            Select::make('columns')
                ->label(__('layup::widgets.feature-grid.columns'))
                ->options(['2' => __('layup::widgets.feature-grid.2'), '3' => __('layup::widgets.feature-grid.3'), '4' => __('layup::widgets.feature-grid.4')])
                ->default('3'),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['features' => [], 'columns' => '3'];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['features'] ?? []);

        return "Feature Grid ({$count})";
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
