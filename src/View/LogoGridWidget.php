<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class LogoGridWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'logo-grid';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.logo-grid');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-building-office-2';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('layup::widgets.logo-grid.title'))
                ->placeholder(__('layup::widgets.logo-grid.trusted_by_leading_companies'))
                ->nullable(),
            FileUpload::make('logos')
                ->label(__('layup::widgets.logo-grid.logos'))
                ->image()
                ->multiple()
                ->reorderable()
                ->directory('layup/logos')
                ->columnSpanFull(),
            Select::make('columns')
                ->label(__('layup::widgets.logo-grid.columns'))
                ->options(['3' => __('layup::widgets.logo-grid.3'), '4' => __('layup::widgets.logo-grid.4'), '5' => __('layup::widgets.logo-grid.5'), '6' => __('layup::widgets.logo-grid.6')])
                ->default('4'),
            Select::make('max_height')
                ->label(__('layup::widgets.logo-grid.logo_max_height'))
                ->options(['2rem' => __('layup::widgets.logo-grid.small'),
                    '3rem' => __('layup::widgets.logo-grid.medium'),
                    '4rem' => __('layup::widgets.logo-grid.large'),
                    '5rem' => __('layup::widgets.logo-grid.extra_large'), ])
                ->default('3rem'),
            Toggle::make('grayscale')
                ->label(__('layup::widgets.logo-grid.grayscale_color_on_hover'))
                ->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'title' => '',
            'logos' => [],
            'columns' => '4',
            'max_height' => '3rem',
            'grayscale' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['logos'] ?? []);

        return "Logo Grid ({$count} logos)";
    }
}
