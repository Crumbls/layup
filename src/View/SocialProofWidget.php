<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\TextInput;

class SocialProofWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'social-proof';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.social-proof');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-trophy';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('rating')->label(__('layup::widgets.social-proof.rating'))->default('4.9')->required(),
            TextInput::make('review_count')->label(__('layup::widgets.social-proof.review_count'))->default('2,300+')->required(),
            TextInput::make('platform')->label(__('layup::widgets.social-proof.platform'))->default('Trustpilot')->required(),
            TextInput::make('badge_text')->label(__('layup::widgets.social-proof.badge_text'))->default('Excellent')->nullable(),
            TextInput::make('link_url')->label(__('layup::widgets.social-proof.link_url'))->url()->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['rating' => '4.9', 'review_count' => '2,300+', 'platform' => 'Trustpilot', 'badge_text' => 'Excellent', 'link_url' => ''];
    }

    public static function getPreview(array $data): string
    {
        return '' . ($data['rating'] ?? '4.9') . ' on ' . ($data['platform'] ?? 'Trustpilot');
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
