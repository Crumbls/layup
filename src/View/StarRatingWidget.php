<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class StarRatingWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'star-rating';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.star-rating');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-star';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Select::make('rating')
                ->label(__('layup::widgets.star-rating.rating'))
                ->options(['0.5' => __('layup::widgets.star-rating.'), '1' => __('layup::widgets.star-rating.1'), '1.5' => __('layup::widgets.star-rating.1_5_1'), '2' => __('layup::widgets.star-rating.2'), '2.5' => __('layup::widgets.star-rating.2_5_2'),
                    '3' => __('layup::widgets.star-rating.3'), '3.5' => __('layup::widgets.star-rating.3_5_3'), '4' => __('layup::widgets.star-rating.4'), '4.5' => __('layup::widgets.star-rating.4_5_4'), '5' => __('layup::widgets.star-rating.5'), ])
                ->default('5')
                ->required(),
            Select::make('max')
                ->label(__('layup::widgets.star-rating.max_stars'))
                ->options(['5' => __('layup::widgets.star-rating.5'), '10' => __('layup::widgets.star-rating.10')])
                ->default('5'),
            TextInput::make('label')
                ->label(__('layup::widgets.star-rating.label'))
                ->placeholder(__('layup::widgets.star-rating.e_g_4_8_out_of_5'))
                ->nullable(),
            Select::make('size')
                ->label(__('layup::widgets.star-rating.size'))
                ->options(['sm' => __('layup::widgets.star-rating.small'), 'md' => __('layup::widgets.star-rating.medium'), 'lg' => __('layup::widgets.star-rating.large')])
                ->default('md'),
            ColorPicker::make('color')
                ->label(__('layup::widgets.star-rating.star_color')),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'rating' => '5',
            'max' => '5',
            'label' => '',
            'size' => 'md',
            'color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        return '⭐ ' . ($data['rating'] ?? 5) . '/' . ($data['max'] ?? 5);
    }
}
