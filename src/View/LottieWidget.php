<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class LottieWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'lottie';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.lottie');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-play';
    }

    public static function getCategory(): string
    {
        return 'media';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('src')
                ->label(__('layup::widgets.lottie.lottie_json_url'))
                ->url()
                ->helperText(__('layup::widgets.lottie.url_to_a_json_lottie_file_from_lottiefiles_etc'))
                ->required()
                ->columnSpanFull(),
            Toggle::make('autoplay')
                ->label(__('layup::widgets.lottie.autoplay'))
                ->default(true),
            Toggle::make('loop')
                ->label(__('layup::widgets.lottie.loop'))
                ->default(true),
            Select::make('width')
                ->label(__('layup::widgets.lottie.width'))
                ->options(['100px' => __('layup::widgets.lottie.tiny'),
                    '200px' => __('layup::widgets.lottie.small'),
                    '300px' => __('layup::widgets.lottie.medium'),
                    '400px' => __('layup::widgets.lottie.large'),
                    '100%' => __('layup::widgets.lottie.full_width'), ])
                ->default('300px'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'src' => '',
            'autoplay' => true,
            'loop' => true,
            'width' => '300px',
        ];
    }

    public static function getPreview(array $data): string
    {
        return 'Lottie Animation';
    }
}
