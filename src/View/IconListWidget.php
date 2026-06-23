<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class IconListWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'icon-list';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.icon-list');
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
            Repeater::make('items')->label(__('layup::widgets.icon-list.items'))->schema([
                TextInput::make('icon')->label(__('layup::widgets.icon-list.emoji_icon'))->default('✅'),
                TextInput::make('text')->label(__('layup::widgets.icon-list.text'))->required(),
                TextInput::make('description')->label(__('layup::widgets.icon-list.description'))->nullable(),
            ])->defaultItems(4)->columnSpanFull(),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['items' => []];
    }

    public static function getPreview(array $data): string
    {
        return '📋 Icon List (' . count($data['items'] ?? []) . ')';
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
