<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BeforeAfterWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'before-after';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.before-after');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-arrows-right-left';
    }

    public static function getCategory(): string
    {
        return 'media';
    }

    public static function getContentFormSchema(): array
    {
        return [
            FileUpload::make('before_image')
                ->label(__('layup::widgets.before-after.before_image'))
                ->image()
                ->directory('layup/before-after')
                ->required(),
            FileUpload::make('after_image')
                ->label(__('layup::widgets.before-after.after_image'))
                ->image()
                ->directory('layup/before-after')
                ->required(),
            TextInput::make('before_label')
                ->label(__('layup::widgets.before-after.before_label'))
                ->default('Before'),
            TextInput::make('after_label')
                ->label(__('layup::widgets.before-after.after_label'))
                ->default('After'),
            Select::make('initial_position')
                ->label(__('layup::widgets.before-after.initial_slider_position'))
                ->options(['25' => __('layup::widgets.before-after.25'),
                    '50' => __('layup::widgets.before-after.50'),
                    '75' => __('layup::widgets.before-after.75'), ])
                ->default('50'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'before_image' => '',
            'after_image' => '',
            'before_label' => 'Before',
            'after_label' => 'After',
            'initial_position' => '50',
        ];
    }

    public static function getPreview(array $data): string
    {
        return '↔ Before / After comparison';
    }
}
