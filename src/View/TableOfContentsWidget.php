<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class TableOfContentsWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'table-of-contents';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.table-of-contents');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-list-bullet';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('layup::widgets.table-of-contents.title'))
                ->default('Table of Contents'),
            Select::make('heading_levels')
                ->label(__('layup::widgets.table-of-contents.heading_levels_to_include'))
                ->multiple()
                ->options(['h2' => __('layup::widgets.table-of-contents.h2'), 'h3' => __('layup::widgets.table-of-contents.h3'), 'h4' => __('layup::widgets.table-of-contents.h4')])
                ->default(['h2', 'h3']),
            Toggle::make('numbered')
                ->label(__('layup::widgets.table-of-contents.numbered_list'))
                ->default(true),
            Toggle::make('collapsible')
                ->label(__('layup::widgets.table-of-contents.collapsible'))
                ->default(false),
            Toggle::make('sticky')
                ->label(__('layup::widgets.table-of-contents.sticky_position'))
                ->default(false),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'title' => 'Table of Contents',
            'heading_levels' => ['h2', 'h3'],
            'numbered' => true,
            'collapsible' => false,
            'sticky' => false,
        ];
    }

    public static function getPreview(array $data): string
    {
        return 'Table of Contents';
    }
}
