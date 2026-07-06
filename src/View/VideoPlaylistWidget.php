<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class VideoPlaylistWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'video-playlist';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.video-playlist');
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
            Repeater::make('videos')
                ->label(__('layup::widgets.video-playlist.videos'))
                ->schema([
                    TextInput::make('title')->label(__('layup::widgets.video-playlist.title'))->required(),
                    TextInput::make('url')->label(__('layup::widgets.video-playlist.youtube_vimeo_url'))->url()->required(),
                    TextInput::make('duration')->label(__('layup::widgets.video-playlist.duration'))->placeholder(__('layup::widgets.video-playlist.3_45'))->nullable(),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            Select::make('layout')
                ->label(__('layup::widgets.video-playlist.layout'))
                ->options(['list' => __('layup::widgets.video-playlist.list_with_player'),
                    'grid' => __('layup::widgets.video-playlist.3_column_grid'), ])
                ->default('list'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'videos' => [],
            'layout' => 'list',
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['videos'] ?? []);

        return "Video Playlist ({$count} videos)";
    }
}
