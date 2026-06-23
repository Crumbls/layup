<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TestimonialGridWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'testimonial-grid';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.testimonial-grid');
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
                ->label(__('layup::widgets.testimonial-grid.testimonials'))
                ->schema([
                    TextInput::make('quote')->label(__('layup::widgets.testimonial-grid.quote'))->required(),
                    TextInput::make('name')->label(__('layup::widgets.testimonial-grid.name'))->required(),
                    TextInput::make('role')->label(__('layup::widgets.testimonial-grid.role'))->nullable(),
                    Select::make('rating')->label(__('layup::widgets.testimonial-grid.rating'))->options(['3' => __('layup::widgets.testimonial-grid.'), '4' => __('layup::widgets.testimonial-grid.4_'), '5' => __('layup::widgets.testimonial-grid.5_')])->default('5'),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            Select::make('columns')
                ->label(__('layup::widgets.testimonial-grid.columns'))
                ->options(['1' => __('layup::widgets.testimonial-grid.1'), '2' => __('layup::widgets.testimonial-grid.2'), '3' => __('layup::widgets.testimonial-grid.3')])
                ->default('3'),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['testimonials' => [], 'columns' => '3'];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['testimonials'] ?? []);

        return "💬 Testimonial Grid ({$count})";
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
