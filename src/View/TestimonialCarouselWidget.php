<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class TestimonialCarouselWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'testimonial-carousel';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.testimonial-carousel');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-chat-bubble-left-right';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('testimonials')
                ->label(__('layup::widgets.testimonial-carousel.testimonials'))
                ->schema([
                    TextInput::make('quote')
                        ->label(__('layup::widgets.testimonial-carousel.quote'))
                        ->required(),
                    TextInput::make('author')
                        ->label(__('layup::widgets.testimonial-carousel.author'))
                        ->required(),
                    TextInput::make('role')
                        ->label(__('layup::widgets.testimonial-carousel.role_company'))
                        ->nullable(),
                    FileUpload::make('photo')
                        ->label(__('layup::widgets.testimonial-carousel.photo'))
                        ->image()
                        ->avatar()
                        ->directory('layup/testimonials'),
                    Select::make('rating')
                        ->label(__('layup::widgets.testimonial-carousel.rating'))
                        ->options(['1' => __('layup::widgets.testimonial-carousel.'), '2' => __('layup::widgets.testimonial-carousel.2_'), '3' => __('layup::widgets.testimonial-carousel.3_'), '4' => __('layup::widgets.testimonial-carousel.4_'), '5' => __('layup::widgets.testimonial-carousel.5_')])
                        ->default('5'),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            Toggle::make('autoplay')
                ->label(__('layup::widgets.testimonial-carousel.autoplay'))
                ->default(true),
            TextInput::make('speed')
                ->label(__('layup::widgets.testimonial-carousel.interval_ms'))
                ->numeric()
                ->default(5000),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'testimonials' => [],
            'autoplay' => true,
            'speed' => 5000,
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['testimonials'] ?? []);

        return "Testimonial Carousel ({$count} slides)";
    }
}
