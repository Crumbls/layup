<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;

class TextColumnsWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'text-columns';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.text-columns');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-view-columns';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            RichEditor::make('content')->label(__('layup::widgets.text-columns.content'))->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'h2', 'h3'])->columnSpanFull(),
            Select::make('columns')->label(__('layup::widgets.text-columns.columns'))->options(['2' => __('layup::widgets.text-columns.2'), '3' => __('layup::widgets.text-columns.3'), '4' => __('layup::widgets.text-columns.4')])->default('2'),
            Select::make('gap')->label(__('layup::widgets.text-columns.gap'))->options(['1rem' => __('layup::widgets.text-columns.small'), '2rem' => __('layup::widgets.text-columns.medium'), '3rem' => __('layup::widgets.text-columns.large')])->default('2rem'),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['content' => '', 'columns' => '2', 'gap' => '2rem'];
    }

    public static function getPreview(array $data): string
    {
        return 'Text Columns (' . ($data['columns'] ?? 2) . ')';
    }
}
