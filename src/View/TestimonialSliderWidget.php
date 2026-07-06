<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class TestimonialSliderWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'testimonial-slider';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.testimonial-slider');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-chat-bubble-bottom-center-text';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('testimonials')
                ->label(__('layup::widgets.testimonial-slider.testimonials'))
                ->schema([
                    Textarea::make('quote')
                        ->label(__('layup::widgets.testimonial-slider.quote'))
                        ->required()
                        ->rows(3),
                    TextInput::make('name')
                        ->label(__('layup::widgets.testimonial-slider.name'))
                        ->required(),
                    TextInput::make('title')
                        ->label(__('layup::widgets.testimonial-slider.title_company'))
                        ->nullable(),
                    FileUpload::make('avatar')
                        ->label(__('layup::widgets.testimonial-slider.avatar'))
                        ->image()
                        ->avatar()
                        ->directory('layup/testimonials'),
                    TextInput::make('rating')
                        ->label(__('layup::widgets.testimonial-slider.rating_1_5'))
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(5)
                        ->nullable(),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            Select::make('autoplay_speed')
                ->label(__('layup::widgets.testimonial-slider.autoplay_speed'))
                ->options(['0' => __('layup::widgets.testimonial-slider.no_autoplay'),
                    '3000' => __('layup::widgets.testimonial-slider.3_seconds'),
                    '5000' => __('layup::widgets.testimonial-slider.5_seconds'),
                    '8000' => __('layup::widgets.testimonial-slider.8_seconds'), ])
                ->default('5000'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'testimonials' => [],
            'autoplay_speed' => '5000',
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['testimonials'] ?? []);

        return "Testimonial Slider ({$count})";
    }
}
