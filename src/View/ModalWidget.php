<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ModalWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'modal';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.modal');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-window';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('trigger_text')
                ->label(__('layup::widgets.modal.trigger_button_text'))
                ->default('Open')
                ->required(),
            TextInput::make('title')
                ->label(__('layup::widgets.modal.modal_title'))
                ->nullable(),
            RichEditor::make('body')
                ->label(__('layup::widgets.modal.modal_content'))
                ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList'])
                ->columnSpanFull(),
            Select::make('size')
                ->label(__('layup::widgets.modal.size'))
                ->options(['sm' => __('layup::widgets.modal.small_400px'),
                    'md' => __('layup::widgets.modal.medium_600px'),
                    'lg' => __('layup::widgets.modal.large_800px'),
                    'xl' => __('layup::widgets.modal.extra_large_1000px'), ])
                ->default('md'),
            ColorPicker::make('trigger_bg_color')
                ->label(__('layup::widgets.modal.trigger_button_color'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'trigger_text' => 'Open',
            'title' => '',
            'body' => '',
            'size' => 'md',
            'trigger_bg_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        return '🪟 Modal: ' . ($data['trigger_text'] ?? 'Open');
    }
}
