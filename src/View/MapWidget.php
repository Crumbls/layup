<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class MapWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'map';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.map');
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
            TextInput::make('address')
                ->label(__('layup::widgets.map.address'))
                ->helperText(__('layup::widgets.map.enter_an_address_or_place_name'))
                ->nullable(),
            Textarea::make('embed')
                ->label(__('layup::widgets.map.embed_code'))
                ->helperText(__('layup::widgets.map.paste_a_google_maps_or_other_embed_iframe_override'))
                ->rows(4)
                ->nullable()
                ->columnSpanFull(),
            Select::make('height')
                ->label(__('layup::widgets.map.height'))
                ->options(['200px' => __('layup::widgets.map.small_200px'),
                    '300px' => __('layup::widgets.map.medium_300px'),
                    '400px' => __('layup::widgets.map.large_400px'),
                    '500px' => __('layup::widgets.map.extra_large_500px'), ])
                ->default('300px'),
            Select::make('zoom')
                ->label(__('layup::widgets.map.zoom_level'))
                ->options(['10' => __('layup::widgets.map.city'),
                    '13' => __('layup::widgets.map.neighborhood'),
                    '15' => __('layup::widgets.map.street'),
                    '18' => __('layup::widgets.map.building'), ])
                ->default('13'),
            Select::make('map_type')
                ->label(__('layup::widgets.map.map_type'))
                ->options(['roadmap' => __('layup::widgets.map.roadmap'),
                    'satellite' => __('layup::widgets.map.satellite'),
                    'terrain' => __('layup::widgets.map.terrain'),
                    'hybrid' => __('layup::widgets.map.hybrid'), ])
                ->default('roadmap'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'address' => '',
            'embed' => '',
            'height' => '300px',
            'zoom' => '13',
            'map_type' => 'roadmap',
        ];
    }

    public static function getPreview(array $data): string
    {
        $address = $data['address'] ?? '';

        return $address ? "{$address}" : '(no address)';
    }
}
