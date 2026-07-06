<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;

class AudioWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'audio';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.audio');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-musical-note';
    }

    public static function getCategory(): string
    {
        return 'media';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('layup::widgets.audio.title'))
                ->nullable(),
            TextInput::make('artist')
                ->label(__('layup::widgets.audio.artist'))
                ->nullable(),
            FileUpload::make('file')
                ->label(__('layup::widgets.audio.audio_file'))
                ->acceptedFileTypes(['audio/*'])
                ->directory('layup/audio'),
            TextInput::make('url')
                ->label(__('layup::widgets.audio.or_audio_url'))
                ->url()
                ->nullable()
                ->helperText(__('layup::widgets.audio.direct_link_to_an_audio_file_used_if_no_file_is_up')),
            FileUpload::make('cover')
                ->label(__('layup::widgets.audio.cover_art'))
                ->image()
                ->directory('layup/audio'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'title' => '',
            'artist' => '',
            'file' => '',
            'url' => '',
            'cover' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        $title = $data['title'] ?? '';
        $artist = $data['artist'] ?? '';

        if ($title) {
            return "{$title}" . ($artist ? " — {$artist}" : '');
        }

        return '(no audio)';
    }
}
