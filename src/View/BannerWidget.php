<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BannerWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'banner';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.banner');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-megaphone';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('heading')
                ->label(__('layup::widgets.banner.heading'))
                ->required(),
            TextInput::make('subtext')
                ->label(__('layup::widgets.banner.subtext'))
                ->nullable(),
            TextInput::make('cta_text')
                ->label(__('layup::widgets.banner.cta_text'))
                ->nullable(),
            TextInput::make('cta_url')
                ->label(__('layup::widgets.banner.cta_url'))
                ->url()
                ->nullable(),
            FileUpload::make('bg_image')
                ->label(__('layup::widgets.banner.background_image'))
                ->image()
                ->directory('layup/banners'),
            ColorPicker::make('bg_color')
                ->label(__('layup::widgets.banner.background_color'))
                ->default(null),
            ColorPicker::make('text_color_banner')
                ->label(__('layup::widgets.banner.text_color'))
                ->default(null),
            Select::make('height')
                ->label(__('layup::widgets.banner.height'))
                ->options(['auto' => __('layup::widgets.banner.auto'), '200px' => __('layup::widgets.banner.small'), '300px' => __('layup::widgets.banner.medium'), '400px' => __('layup::widgets.banner.large')])
                ->default('auto'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'heading' => '',
            'subtext' => '',
            'cta_text' => '',
            'cta_url' => '',
            'bg_image' => '',
            'bg_color' => null,
            'text_color_banner' => null,
            'height' => 'auto',
        ];
    }

    public static function getPreview(array $data): string
    {
        return '📣 ' . ($data['heading'] ?? '(empty banner)');
    }
}
