<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class SearchWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'search';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.search');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-magnifying-glass';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('placeholder')
                ->label(__('layup::widgets.search.placeholder_text'))
                ->default('Search...')
                ->nullable(),
            TextInput::make('action')
                ->label(__('layup::widgets.search.form_action_url'))
                ->helperText(__('layup::widgets.search.where_the_search_form_submits_to'))
                ->default('/search')
                ->nullable(),
            TextInput::make('param')
                ->label(__('layup::widgets.search.query_parameter'))
                ->default('q')
                ->nullable(),
            Select::make('size')
                ->label(__('layup::widgets.search.size'))
                ->options(['sm' => __('layup::widgets.search.small'),
                    'md' => __('layup::widgets.search.medium'),
                    'lg' => __('layup::widgets.search.large'), ])
                ->default('md'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'placeholder' => 'Search...',
            'action' => '/search',
            'param' => 'q',
            'size' => 'md',
        ];
    }

    public static function getPreview(array $data): string
    {
        return 'Search form';
    }
}
