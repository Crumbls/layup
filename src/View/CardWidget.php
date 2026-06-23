<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class CardWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'card';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.card');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-rectangle-stack';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            FileUpload::make('image')
                ->label(__('layup::widgets.card.image'))
                ->image()
                ->directory('layup/cards'),
            TextInput::make('title')
                ->label(__('layup::widgets.card.title'))
                ->required(),
            RichEditor::make('body')
                ->label(__('layup::widgets.card.body'))
                ->toolbarButtons(['bold', 'italic', 'link'])
                ->columnSpanFull(),
            TextInput::make('link_url')
                ->label(__('layup::widgets.card.link_url'))
                ->url()
                ->nullable(),
            TextInput::make('link_text')
                ->label(__('layup::widgets.card.link_text'))
                ->default('Learn more')
                ->nullable(),
            Toggle::make('shadow')
                ->label(__('layup::widgets.card.drop_shadow'))
                ->default(true),
            Toggle::make('hover_lift')
                ->label(__('layup::widgets.card.hover_lift_effect'))
                ->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'image' => '',
            'title' => '',
            'body' => '',
            'link_url' => '',
            'link_text' => 'Learn more',
            'shadow' => true,
            'hover_lift' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        return '🃏 ' . ($data['title'] ?? '(empty card)');
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
