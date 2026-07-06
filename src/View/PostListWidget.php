<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class PostListWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'post-list';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.post-list');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-document-text';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('model')
                ->label(__('layup::widgets.post-list.model_class'))
                ->placeholder(__('layup::widgets.post-list.app_models_post'))
                ->helperText(__('layup::widgets.post-list.eloquent_model_with_title_slug_excerpt_published_a'))
                ->nullable(),
            TextInput::make('limit')
                ->label(__('layup::widgets.post-list.number_of_posts'))
                ->numeric()
                ->default(6)
                ->minValue(1)
                ->maxValue(50),
            Select::make('columns')
                ->label(__('layup::widgets.post-list.columns'))
                ->options(['1' => __('layup::widgets.post-list.1'), '2' => __('layup::widgets.post-list.2'), '3' => __('layup::widgets.post-list.3')])
                ->default('3'),
            Select::make('order')
                ->label(__('layup::widgets.post-list.order_by'))
                ->options(['latest' => __('layup::widgets.post-list.newest_first'),
                    'oldest' => __('layup::widgets.post-list.oldest_first'),
                    'title' => __('layup::widgets.post-list.title_a_z'), ])
                ->default('latest'),
            Toggle::make('show_excerpt')
                ->label(__('layup::widgets.post-list.show_excerpt'))
                ->default(true),
            Toggle::make('show_date')
                ->label(__('layup::widgets.post-list.show_date'))
                ->default(true),
            TextInput::make('read_more_text')
                ->label(__('layup::widgets.post-list.read_more_text'))
                ->default('Read more →'),
            TextInput::make('empty_message')
                ->label(__('layup::widgets.post-list.empty_state_message'))
                ->default('No posts yet.'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'model' => '',
            'limit' => 6,
            'columns' => '3',
            'order' => 'latest',
            'show_excerpt' => true,
            'show_date' => true,
            'read_more_text' => 'Read more →',
            'empty_message' => 'No posts yet.',
        ];
    }

    public static function getPreview(array $data): string
    {
        $limit = $data['limit'] ?? 6;

        return "Post List ({$limit} posts)";
    }
}
