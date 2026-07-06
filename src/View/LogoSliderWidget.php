<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class LogoSliderWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'logo-slider';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.logo-slider');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-building-office';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            FileUpload::make('logos')
                ->label(__('layup::widgets.logo-slider.logos'))
                ->image()
                ->multiple()
                ->reorderable()
                ->directory('layup/logo-slider')
                ->columnSpanFull(),
            Select::make('speed')
                ->label(__('layup::widgets.logo-slider.speed'))
                ->options(['40' => __('layup::widgets.logo-slider.slow'), '25' => __('layup::widgets.logo-slider.normal'), '15' => __('layup::widgets.logo-slider.fast')])
                ->default('25'),
            TextInput::make('max_height')
                ->label(__('layup::widgets.logo-slider.logo_max_height'))
                ->default('3rem'),
            TextInput::make('gap')
                ->label(__('layup::widgets.logo-slider.gap_between_logos'))
                ->default('4rem'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'logos' => [],
            'speed' => '25',
            'max_height' => '3rem',
            'gap' => '4rem',
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['logos'] ?? []);

        return "Logo Slider ({$count} logos)";
    }
}
