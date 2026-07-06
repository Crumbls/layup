<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class HighlightBoxWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'highlight-box';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.highlight-box');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-light-bulb';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')->label(__('layup::widgets.highlight-box.title'))->nullable(),
            RichEditor::make('content')->label(__('layup::widgets.highlight-box.content'))->toolbarButtons(['bold', 'italic', 'link', 'bulletList'])->columnSpanFull(),
            Select::make('variant')->label(__('layup::widgets.highlight-box.variant'))->options(['info' => __('layup::widgets.highlight-box.info_blue'), 'tip' => __('layup::widgets.highlight-box.tip_green'), 'warning' => __('layup::widgets.highlight-box.warning_yellow'),
                'important' => __('layup::widgets.highlight-box.important_red'), 'note' => __('layup::widgets.highlight-box.note_gray'), ])->default('info'),
            TextInput::make('icon')->label(__('layup::widgets.highlight-box.custom_icon'))->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['title' => '', 'content' => '', 'variant' => 'info', 'icon' => ''];
    }

    public static function getPreview(array $data): string
    {
        return '' . ($data['title'] ?? 'Highlight Box');
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
