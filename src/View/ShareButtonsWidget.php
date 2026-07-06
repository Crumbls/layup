<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class ShareButtonsWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'share-buttons';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.share-buttons');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-share';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            CheckboxList::make('networks')
                ->label(__('layup::widgets.share-buttons.networks'))
                ->options(['facebook' => __('layup::widgets.share-buttons.facebook'),
                    'twitter' => __('layup::widgets.share-buttons.twitter_x'),
                    'linkedin' => __('layup::widgets.share-buttons.linkedin'),
                    'reddit' => __('layup::widgets.share-buttons.reddit'),
                    'email' => __('layup::widgets.share-buttons.email'),
                    'copy' => __('layup::widgets.share-buttons.copy_link'), ])
                ->default(['facebook', 'twitter', 'linkedin', 'email'])
                ->columns(2),
            Select::make('style')
                ->label(__('layup::widgets.share-buttons.style'))
                ->options(['icon' => __('layup::widgets.share-buttons.icon_only'),
                    'label' => __('layup::widgets.share-buttons.icon_label'),
                    'text' => __('layup::widgets.share-buttons.text_only'), ])
                ->default('icon'),
            Select::make('layout')
                ->label(__('layup::widgets.share-buttons.layout'))
                ->options(['horizontal' => __('layup::widgets.share-buttons.horizontal'),
                    'vertical' => __('layup::widgets.share-buttons.vertical'), ])
                ->default('horizontal'),
            Toggle::make('new_tab')
                ->label(__('layup::widgets.share-buttons.open_in_new_tab'))
                ->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'networks' => ['facebook', 'twitter', 'linkedin', 'email'],
            'style' => 'icon',
            'layout' => 'horizontal',
            'new_tab' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['networks'] ?? []);

        return "Share Buttons ({$count} networks)";
    }
}
