<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Support\WidgetContext;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Storage;

class ImageWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'image';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.image');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-photo';
    }

    public static function getCategory(): string
    {
        return 'media';
    }

    public static function getContentFormSchema(): array
    {
        return [
            FileUpload::make('src')
                ->label(__('layup::widgets.image.image'))
                ->image()
                ->directory('layup/images')
                ->maxSize(config('layup.uploads.max_size', 10240)),
            TextInput::make('alt')
                ->label(__('layup::widgets.image.alt_text')),
            TextInput::make('caption')
                ->label(__('layup::widgets.image.caption')),
            TextInput::make('link_url')
                ->label(__('layup::widgets.image.link_url'))
                ->url()
                ->placeholder(__('layup::widgets.image.https'))
                ->nullable(),
            Checkbox::make('link_new_tab')
                ->label(__('layup::widgets.image.open_in_new_tab'))
                ->default(false),
            Select::make('hover_effect')
                ->label(__('layup::widgets.image.hover_effect'))
                ->options(['' => __('layup::widgets.image.none'),
                    'zoom' => __('layup::widgets.image.zoom_in'),
                    'grayscale' => __('layup::widgets.image.grayscale_color'),
                    'brightness' => __('layup::widgets.image.brighten'),
                    'blur' => __('layup::widgets.image.blur_clear'), ])
                ->default('')
                ->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'src' => '',
            'alt' => '',
            'caption' => '',
            'link_url' => '',
            'link_new_tab' => false,
            'hover_effect' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        if (! empty($data['src'])) {
            $name = is_array($data['src']) ? 'uploaded image' : basename((string) $data['src']);

            return "{$name}";
        }

        return '(no image)';
    }

    public static function onDelete(array $data, ?WidgetContext $context = null): void
    {
        if (empty($data['src']) || ! is_string($data['src'])) {
            return;
        }

        $src = $data['src'];

        if (str_contains($src, '..') || str_contains($src, "\0") || str_starts_with($src, '/')) {
            return;
        }

        $disk = config('layup.uploads.disk', 'public');
        Storage::disk($disk)->delete($src);
    }
}
