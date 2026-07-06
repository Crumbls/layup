<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class SocialFollowWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'social-follow';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.social-follow');
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
            Repeater::make('links')
                ->label(__('layup::widgets.social-follow.social_links'))
                ->schema([
                    Select::make('network')
                        ->label(__('layup::widgets.social-follow.network'))
                        ->options(['facebook' => __('layup::widgets.social-follow.facebook'),
                            'twitter' => __('layup::widgets.social-follow.x_twitter'),
                            'instagram' => __('layup::widgets.social-follow.instagram'),
                            'linkedin' => __('layup::widgets.social-follow.linkedin'),
                            'youtube' => __('layup::widgets.social-follow.youtube'),
                            'tiktok' => __('layup::widgets.social-follow.tiktok'),
                            'pinterest' => __('layup::widgets.social-follow.pinterest'),
                            'github' => __('layup::widgets.social-follow.github'),
                            'dribbble' => __('layup::widgets.social-follow.dribbble'),
                            'email' => __('layup::widgets.social-follow.email'), ])
                        ->required(),
                    TextInput::make('url')
                        ->label(__('layup::widgets.social-follow.url'))
                        ->required(),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            Select::make('style')
                ->label(__('layup::widgets.social-follow.style'))
                ->options(['icon' => __('layup::widgets.social-follow.icon_only'),
                    'icon-text' => __('layup::widgets.social-follow.icon_text'),
                    'text' => __('layup::widgets.social-follow.text_only'), ])
                ->default('icon'),
            Toggle::make('new_tab')
                ->label(__('layup::widgets.social-follow.open_in_new_tab'))
                ->default(true),
            Select::make('icon_size')
                ->label(__('layup::widgets.social-follow.icon_size'))
                ->options(['sm' => __('layup::widgets.social-follow.small'),
                    'md' => __('layup::widgets.social-follow.medium'),
                    'lg' => __('layup::widgets.social-follow.large'), ])
                ->default('md'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'links' => [],
            'style' => 'icon',
            'new_tab' => true,
            'icon_size' => 'md',
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['links'] ?? []);

        return "Social · {$count} links";
    }
}
