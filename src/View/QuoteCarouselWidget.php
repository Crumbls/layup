<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class QuoteCarouselWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'quote-carousel';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.quote-carousel');
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
            Repeater::make('quotes')->label(__('layup::widgets.quote-carousel.quotes'))->schema([
                TextInput::make('text')->label(__('layup::widgets.quote-carousel.quote'))->required(),
                TextInput::make('author')->label(__('layup::widgets.quote-carousel.author'))->nullable(),
            ])->defaultItems(3)->columnSpanFull(),
            TextInput::make('interval')->label(__('layup::widgets.quote-carousel.auto_play_interval_seconds'))->numeric()->default(5),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['quotes' => [], 'interval' => 5];
    }

    public static function getPreview(array $data): string
    {
        return 'Quote Carousel (' . count($data['quotes'] ?? []) . ')';
    }
}
