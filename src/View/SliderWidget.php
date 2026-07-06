<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class SliderWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'slider';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.slider');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-presentation-chart-bar';
    }

    public static function getCategory(): string
    {
        return 'media';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('slides')
                ->label(__('layup::widgets.slider.slides'))
                ->schema([
                    TextInput::make('heading')
                        ->label(__('layup::widgets.slider.heading'))
                        ->nullable(),
                    RichEditor::make('content')
                        ->label(__('layup::widgets.slider.content'))
                        ->columnSpanFull(),
                    FileUpload::make('image')
                        ->label(__('layup::widgets.slider.background_image'))
                        ->image()
                        ->directory('layup/slider'),
                    TextInput::make('button_text')
                        ->label(__('layup::widgets.slider.button_text'))
                        ->nullable(),
                    TextInput::make('button_url')
                        ->label(__('layup::widgets.slider.button_url'))
                        ->url()
                        ->nullable(),
                ])
                ->defaultItems(2)
                ->collapsible()
                ->columnSpanFull(),
            Toggle::make('autoplay')
                ->label(__('layup::widgets.slider.autoplay'))
                ->default(true),
            Select::make('speed')
                ->label(__('layup::widgets.slider.slide_duration'))
                ->options(['3000' => __('layup::widgets.slider.3_seconds'),
                    '5000' => __('layup::widgets.slider.5_seconds'),
                    '7000' => __('layup::widgets.slider.7_seconds'),
                    '10000' => __('layup::widgets.slider.10_seconds'), ])
                ->default('5000'),
            Toggle::make('arrows')
                ->label(__('layup::widgets.slider.show_arrows'))
                ->default(true),
            Toggle::make('dots')
                ->label(__('layup::widgets.slider.show_dots'))
                ->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'slides' => [
                ['heading' => '', 'content' => '', 'image' => '', 'button_text' => '', 'button_url' => ''],
                ['heading' => '', 'content' => '', 'image' => '', 'button_text' => '', 'button_url' => ''],
            ],
            'autoplay' => true,
            'speed' => '5000',
            'arrows' => true,
            'dots' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['slides'] ?? []);

        return "Slider · {$count} slides";
    }
}
