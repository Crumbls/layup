<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class HeroWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'hero';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.hero');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-rectangle-group';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('heading')
                ->label(__('layup::widgets.hero.heading'))
                ->required()
                ->columnSpanFull(),
            TextInput::make('subheading')
                ->label(__('layup::widgets.hero.subheading'))
                ->nullable()
                ->columnSpanFull(),
            RichEditor::make('description')
                ->label(__('layup::widgets.hero.description'))
                ->toolbarButtons(['bold', 'italic', 'link'])
                ->columnSpanFull(),
            TextInput::make('primary_button_text')
                ->label(__('layup::widgets.hero.primary_button_text'))
                ->nullable(),
            TextInput::make('primary_button_url')
                ->label(__('layup::widgets.hero.primary_button_url'))
                ->url()
                ->nullable(),
            TextInput::make('secondary_button_text')
                ->label(__('layup::widgets.hero.secondary_button_text'))
                ->nullable(),
            TextInput::make('secondary_button_url')
                ->label(__('layup::widgets.hero.secondary_button_url'))
                ->url()
                ->nullable(),
            FileUpload::make('background_image')
                ->label(__('layup::widgets.hero.background_image'))
                ->image()
                ->directory('layup/heroes'),
            Select::make('alignment')
                ->label(__('layup::widgets.hero.content_alignment'))
                ->options(['left' => __('layup::widgets.hero.left'), 'center' => __('layup::widgets.hero.center'), 'right' => __('layup::widgets.hero.right')])
                ->default('center'),
            Select::make('height')
                ->label(__('layup::widgets.hero.height'))
                ->options(['auto' => __('layup::widgets.hero.auto'),
                    '50vh' => __('layup::widgets.hero.half_screen'),
                    '75vh' => __('layup::widgets.hero.three_quarter_screen'),
                    '100vh' => __('layup::widgets.hero.full_screen'), ])
                ->default('auto'),
            ColorPicker::make('overlay_color')
                ->label(__('layup::widgets.hero.overlay_color'))
                ->default('#000000'),
            TextInput::make('overlay_opacity')
                ->label(__('layup::widgets.hero.overlay_opacity_0_100'))
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(50),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'heading' => '',
            'subheading' => '',
            'description' => '',
            'primary_button_text' => '',
            'primary_button_url' => '#',
            'secondary_button_text' => '',
            'secondary_button_url' => '#',
            'background_image' => '',
            'alignment' => 'center',
            'height' => 'auto',
            'overlay_color' => '#000000',
            'overlay_opacity' => 50,
        ];
    }

    public static function getPreview(array $data): string
    {
        return '🦸 ' . ($data['heading'] ?? '(empty hero)');
    }
}
