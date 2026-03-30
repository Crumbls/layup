<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class BlockquoteWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'blockquote';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.blockquote');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-chat-bubble-left';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Textarea::make('quote')
                ->label(__('layup::widgets.blockquote.quote_text'))
                ->required()
                ->rows(4)
                ->columnSpanFull(),
            TextInput::make('attribution')
                ->label(__('layup::widgets.blockquote.attribution'))
                ->placeholder(__('layup::widgets.blockquote.author_name'))
                ->nullable(),
            TextInput::make('source')
                ->label(__('layup::widgets.blockquote.source'))
                ->placeholder(__('layup::widgets.blockquote.book_article_speech'))
                ->nullable(),
            Select::make('style')
                ->label(__('layup::widgets.blockquote.style'))
                ->options(['border-left' => __('layup::widgets.blockquote.left_border'),
                    'large' => __('layup::widgets.blockquote.large_quote'),
                    'centered' => __('layup::widgets.blockquote.centered'), ])
                ->default('border-left'),
            ColorPicker::make('accent_color')
                ->label(__('layup::widgets.blockquote.accent_color'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'quote' => '',
            'attribution' => '',
            'source' => '',
            'style' => 'border-left',
            'accent_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        $quote = $data['quote'] ?? '';
        $short = mb_strlen($quote) > 50 ? mb_substr($quote, 0, 50) . '…' : $quote;

        return "❝ {$short}";
    }
}
