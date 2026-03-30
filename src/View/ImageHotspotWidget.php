<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class ImageHotspotWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'image-hotspot';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.image-hotspot');
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
            FileUpload::make('image')
                ->label(__('layup::widgets.image-hotspot.image'))
                ->image()
                ->directory('layup/hotspots')
                ->required(),
            Repeater::make('hotspots')
                ->label(__('layup::widgets.image-hotspot.hotspots'))
                ->schema([
                    TextInput::make('x')
                        ->label(__('layup::widgets.image-hotspot.x_position'))
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required(),
                    TextInput::make('y')
                        ->label(__('layup::widgets.image-hotspot.y_position'))
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required(),
                    TextInput::make('title')
                        ->label(__('layup::widgets.image-hotspot.title'))
                        ->required(),
                    TextInput::make('description')
                        ->label(__('layup::widgets.image-hotspot.description'))
                        ->nullable(),
                    TextInput::make('link_url')
                        ->label(__('layup::widgets.image-hotspot.link_url'))
                        ->url()
                        ->nullable(),
                ])
                ->defaultItems(0)
                ->columnSpanFull(),
            ColorPicker::make('pin_color')
                ->label(__('layup::widgets.image-hotspot.pin_color'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'image' => '',
            'hotspots' => [],
            'pin_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['hotspots'] ?? []);

        return "📍 Image Hotspot ({$count} pins)";
    }
}
