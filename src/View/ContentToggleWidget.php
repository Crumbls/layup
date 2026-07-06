<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ContentToggleWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'content-toggle';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.content-toggle');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-eye-slash';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('show_text')
                ->label(__('layup::widgets.content-toggle.show_button_text'))
                ->default('Show more'),
            TextInput::make('hide_text')
                ->label(__('layup::widgets.content-toggle.hide_button_text'))
                ->default('Show less'),
            RichEditor::make('content')
                ->label(__('layup::widgets.content-toggle.hidden_content'))
                ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList'])
                ->columnSpanFull(),
            Toggle::make('start_open')
                ->label(__('layup::widgets.content-toggle.start_expanded'))
                ->default(false),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'show_text' => 'Show more',
            'hide_text' => 'Show less',
            'content' => '',
            'start_open' => false,
        ];
    }

    public static function getPreview(array $data): string
    {
        return 'Content Toggle';
    }
}
