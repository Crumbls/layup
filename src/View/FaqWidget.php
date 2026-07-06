<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class FaqWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'faq';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.faq');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-question-mark-circle';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('items')
                ->label(__('layup::widgets.faq.faq_items'))
                ->schema([
                    TextInput::make('question')
                        ->label(__('layup::widgets.faq.question'))
                        ->required(),
                    Textarea::make('answer')
                        ->label(__('layup::widgets.faq.answer'))
                        ->required()
                        ->rows(3),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            Select::make('style')
                ->label(__('layup::widgets.faq.style'))
                ->options(['accordion' => __('layup::widgets.faq.accordion_expand_collapse'),
                    'list' => __('layup::widgets.faq.plain_list_always_visible'),
                    'cards' => __('layup::widgets.faq.cards'), ])
                ->default('accordion'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'items' => [],
            'style' => 'accordion',
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['items'] ?? []);

        return "FAQ ({$count} questions)";
    }
}
