<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;

class FileDownloadWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'file-download';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.file-download');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-arrow-down-tray';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')->label(__('layup::widgets.file-download.title'))->required(),
            TextInput::make('description')->label(__('layup::widgets.file-download.description'))->nullable(),
            FileUpload::make('file')->label(__('layup::widgets.file-download.file'))->directory('layup/downloads')->required(),
            TextInput::make('button_text')->label(__('layup::widgets.file-download.button_text'))->default('Download'),
            TextInput::make('file_size')->label(__('layup::widgets.file-download.file_size_display'))->placeholder(__('layup::widgets.file-download.2_4_mb'))->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['title' => '', 'description' => '', 'file' => '', 'button_text' => 'Download', 'file_size' => ''];
    }

    public static function getPreview(array $data): string
    {
        return '' . ($data['title'] ?? 'File Download');
    }
}
