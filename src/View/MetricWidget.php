<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class MetricWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'metric';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.metric');
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
            Repeater::make('metrics')
                ->label(__('layup::widgets.metric.metrics'))
                ->schema([
                    TextInput::make('value')->label(__('layup::widgets.metric.value'))->required()->placeholder(__('layup::widgets.metric.10k')),
                    TextInput::make('label')->label(__('layup::widgets.metric.label'))->required()->placeholder(__('layup::widgets.metric.users')),
                    TextInput::make('prefix')->label(__('layup::widgets.metric.prefix'))->nullable(),
                    TextInput::make('suffix')->label(__('layup::widgets.metric.suffix'))->nullable(),
                ])
                ->defaultItems(4)
                ->columnSpanFull(),
            Select::make('columns')
                ->label(__('layup::widgets.metric.columns'))
                ->options(['2' => __('layup::widgets.metric.2'), '3' => __('layup::widgets.metric.3'), '4' => __('layup::widgets.metric.4'), '5' => __('layup::widgets.metric.5')])
                ->default('4'),
            Select::make('style')
                ->label(__('layup::widgets.metric.style'))
                ->options(['plain' => __('layup::widgets.metric.plain'), 'bordered' => __('layup::widgets.metric.bordered'), 'cards' => __('layup::widgets.metric.cards')])
                ->default('plain'),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['metrics' => [
            ['value' => '10K+', 'label' => 'Users'],
            ['value' => '99.9%', 'label' => 'Uptime'],
            ['value' => '150+', 'label' => 'Countries'],
            ['value' => '24/7', 'label' => 'Support'],
        ], 'columns' => '4', 'style' => 'plain'];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['metrics'] ?? []);

        return "📊 Metrics ({$count})";
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
