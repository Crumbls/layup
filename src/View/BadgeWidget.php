<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BadgeWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'badge';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.badge');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-tag';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('text')->label(__('layup::widgets.badge.text'))->required(),
            Select::make('variant')->label(__('layup::widgets.badge.variant'))->options(['default' => __('layup::widgets.badge.default'), 'success' => __('layup::widgets.badge.success'), 'warning' => __('layup::widgets.badge.warning'),
                'danger' => __('layup::widgets.badge.danger'), 'info' => __('layup::widgets.badge.info'), 'dark' => __('layup::widgets.badge.dark'), ])->default('default'),
            Select::make('size')->label(__('layup::widgets.badge.size'))->options(['sm' => __('layup::widgets.badge.small'), 'md' => __('layup::widgets.badge.medium'), 'lg' => __('layup::widgets.badge.large')])->default('md'),
            TextInput::make('link_url')->label(__('layup::widgets.badge.link_url'))->url()->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['text' => '', 'variant' => 'default', 'size' => 'md', 'link_url' => ''];
    }

    public static function getPreview(array $data): string
    {
        return '🏷 ' . ($data['text'] ?? 'Badge');
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
