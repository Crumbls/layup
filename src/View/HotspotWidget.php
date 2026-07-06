<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class HotspotWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'hotspot';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.hotspot');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-map-pin';
    }

    public static function getCategory(): string
    {
        return 'media';
    }

    public static function getContentFormSchema(): array
    {
        return [
            FileUpload::make('image')->label(__('layup::widgets.hotspot.background_image'))->image()->directory('layup/hotspots')->required(),
            Repeater::make('points')
                ->label(__('layup::widgets.hotspot.hotspot_points'))
                ->schema([
                    TextInput::make('x')->label(__('layup::widgets.hotspot.x_position'))->numeric()->minValue(0)->maxValue(100)->required(),
                    TextInput::make('y')->label(__('layup::widgets.hotspot.y_position'))->numeric()->minValue(0)->maxValue(100)->required(),
                    TextInput::make('label')->label(__('layup::widgets.hotspot.label'))->required(),
                    TextInput::make('description')->label(__('layup::widgets.hotspot.description'))->nullable(),
                ])
                ->defaultItems(1)
                ->columnSpanFull(),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['image' => '', 'points' => []];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['points'] ?? []);

        return "Hotspot Image ({$count} points)";
    }
}
