<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class BreadcrumbsWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'breadcrumbs';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.breadcrumbs');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-chevron-right';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('items')
                ->label(__('layup::widgets.breadcrumbs.breadcrumb_items'))
                ->schema([
                    TextInput::make('label')
                        ->label(__('layup::widgets.breadcrumbs.label'))
                        ->required(),
                    TextInput::make('url')
                        ->label(__('layup::widgets.breadcrumbs.url'))
                        ->nullable(),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            TextInput::make('separator')
                ->label(__('layup::widgets.breadcrumbs.separator'))
                ->default('/')
                ->maxLength(3),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'items' => [
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Products', 'url' => '/products'],
                ['label' => 'Current Page', 'url' => ''],
            ],
            'separator' => '/',
        ];
    }

    public static function getPreview(array $data): string
    {
        $labels = array_column($data['items'] ?? [], 'label');

        return implode(' > ', array_slice($labels, 0, 3));
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
