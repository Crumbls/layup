<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Support\WidgetContext;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class VideoWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'video';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.video');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-play-circle';
    }

    public static function getCategory(): string
    {
        return 'media';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('url')
                ->label(__('layup::widgets.video.video_url'))
                ->helperText(__('layup::widgets.video.youtube_vimeo_or_direct_video_url'))
                ->required()
                ->url(),
            Select::make('aspect')
                ->label(__('layup::widgets.video.aspect_ratio'))
                ->options(['16/9' => __('layup::widgets.video.16_9_widescreen'),
                    '4/3' => __('layup::widgets.video.4_3_standard'),
                    '1/1' => __('layup::widgets.video.1_1_square'),
                    '21/9' => __('layup::widgets.video.21_9_ultra_wide'), ])
                ->default('16/9'),
            TextInput::make('title')
                ->label(__('layup::widgets.video.title_caption')),
            Toggle::make('autoplay')
                ->label(__('layup::widgets.video.autoplay'))
                ->default(false),
            Toggle::make('loop')
                ->label(__('layup::widgets.video.loop'))
                ->default(false),
            Toggle::make('privacy_enhanced')
                ->label(__('layup::widgets.video.privacy_enhanced_mode_youtube'))
                ->helperText(__('layup::widgets.video.uses_youtube_nocookie_com_domain'))
                ->default(false),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'url' => '',
            'aspect' => '16/9',
            'title' => '',
            'autoplay' => false,
            'loop' => false,
            'privacy_enhanced' => false,
        ];
    }

    public static function getPreview(array $data): string
    {
        $url = $data['url'] ?? '';
        if (! $url) {
            return '▶ (no video)';
        }

        if (str_contains((string) $url, 'youtube') || str_contains((string) $url, 'youtu.be')) {
            return '▶ YouTube · ' . $url;
        }
        if (str_contains((string) $url, 'vimeo')) {
            return '▶ Vimeo · ' . $url;
        }

        return '▶ Video · ' . basename((string) $url);
    }

    /**
     * Normalize YouTube URLs to embed format on save.
     */
    public static function onSave(array $data, ?WidgetContext $context = null): array
    {
        if (! empty($data['url'])) {
            $privacy = ! empty($data['privacy_enhanced']);
            $data['embed_url'] = static::toEmbedUrl($data['url'], $privacy);
        }

        return $data;
    }

    protected static function toEmbedUrl(string $url, bool $privacyEnhanced = false): string
    {
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m)) {
            $domain = $privacyEnhanced ? 'www.youtube-nocookie.com' : 'www.youtube.com';

            return "https://{$domain}/embed/{$m[1]}";
        }

        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m)) {
            return "https://player.vimeo.com/video/{$m[1]}";
        }

        return $url;
    }
}
