<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class TestimonialWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'testimonial';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.testimonial');
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
            Textarea::make('quote')
                ->label(__('layup::widgets.testimonial.quote'))
                ->required()
                ->rows(4)
                ->columnSpanFull(),
            TextInput::make('author')
                ->label(__('layup::widgets.testimonial.author_name'))
                ->required(),
            TextInput::make('role')
                ->label(__('layup::widgets.testimonial.role_company'))
                ->nullable(),
            FileUpload::make('photo')
                ->label(__('layup::widgets.testimonial.author_photo'))
                ->image()
                ->avatar()
                ->directory('layup/testimonials'),
            TextInput::make('url')
                ->label(__('layup::widgets.testimonial.link_url'))
                ->url()
                ->nullable(),
            Select::make('style')
                ->label(__('layup::widgets.testimonial.style'))
                ->options(['default' => __('layup::widgets.testimonial.default'),
                    'card' => __('layup::widgets.testimonial.card'),
                    'minimal' => __('layup::widgets.testimonial.minimal'),
                    'centered' => __('layup::widgets.testimonial.centered'), ])
                ->default('default'),
            TextInput::make('company')
                ->label(__('layup::widgets.testimonial.company_name'))
                ->nullable(),
            Select::make('rating')
                ->label(__('layup::widgets.testimonial.star_rating'))
                ->options(['' => __('layup::widgets.testimonial.none'),
                    '1' => __('layup::widgets.testimonial.'),
                    '2' => __('layup::widgets.testimonial.2_'),
                    '3' => __('layup::widgets.testimonial.3_'),
                    '4' => __('layup::widgets.testimonial.4_'),
                    '5' => __('layup::widgets.testimonial.5_'), ])
                ->default('')
                ->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'quote' => '',
            'author' => '',
            'role' => '',
            'photo' => '',
            'url' => '',
            'style' => 'default',
            'company' => '',
            'rating' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        $author = $data['author'] ?? '';
        $quote = $data['quote'] ?? '';
        $short = mb_strlen($quote) > 40 ? mb_substr($quote, 0, 40) . '…' : $quote;

        return $author ? "💬 {$author}: \"{$short}\"" : '(empty testimonial)';
    }
}
