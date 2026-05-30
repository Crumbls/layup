<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class GalleryWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'gallery';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.gallery');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-squares-2x2';
    }

    public static function getCategory(): string
    {
        return 'media';
    }

    public static function getContentFormSchema(): array
    {
        return [
            FileUpload::make('images')
                ->label(__('layup::widgets.gallery.images'))
                ->image()
                ->multiple()
                ->reorderable()
                ->directory('layup/gallery')
                ->maxSize(config('layup.uploads.max_size', 10240))
                ->columnSpanFull(),
            Select::make('columns')
                ->label(__('layup::widgets.gallery.columns'))
                ->options(['2' => __('layup::widgets.gallery.2_columns'),
                    '3' => __('layup::widgets.gallery.3_columns'),
                    '4' => __('layup::widgets.gallery.4_columns'),
                    '5' => __('layup::widgets.gallery.5_columns'),
                    '6' => __('layup::widgets.gallery.6_columns'), ])
                ->default('3'),
            Select::make('gap')
                ->label(__('layup::widgets.gallery.gap'))
                ->options(['0' => __('layup::widgets.gallery.none'),
                    '0.25rem' => __('layup::widgets.gallery.extra_small'),
                    '0.5rem' => __('layup::widgets.gallery.small'),
                    '1rem' => __('layup::widgets.gallery.medium'),
                    '1.5rem' => __('layup::widgets.gallery.large'), ])
                ->default('0.5rem'),
            Toggle::make('lightbox')
                ->label(__('layup::widgets.gallery.enable_lightbox'))
                ->default(true),
            Toggle::make('show_captions')
                ->label(__('layup::widgets.gallery.show_captions'))
                ->default(false),
            \Filament\Forms\Components\Textarea::make('captions_text')
                ->label(__('layup::widgets.gallery.captions_one_per_line_matching_image_order'))
                ->helperText(__('layup::widgets.gallery.enter_one_caption_per_line_line_1_first_image_etc'))
                ->rows(4)
                ->nullable()
                ->columnSpanFull(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'images' => [],
            'columns' => '3',
            'gap' => '0.5rem',
            'lightbox' => true,
            'show_captions' => false,
            'captions_text' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['images'] ?? []);

        return "🖼 Gallery · {$count} images";
    }
}
