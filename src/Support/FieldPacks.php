<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class FieldPacks
{
    /**
     * Image upload with alt text field.
     *
     * @return array<\Filament\Forms\Components\Component>
     */
    public static function image(string $prefix): array
    {
        return [
            FileUpload::make("{$prefix}_src")
                ->label(__('layup::widgets.shared.image'))
                ->image()
                ->directory('layup/images')
                ->nullable(),
            TextInput::make("{$prefix}_alt")
                ->label(__('layup::widgets.shared.alt_text'))
                ->nullable(),
        ];
    }

    /**
     * Link URL with "open in new tab" toggle.
     *
     * @return array<\Filament\Forms\Components\Component>
     */
    public static function link(string $prefix): array
    {
        return [
            TextInput::make("{$prefix}_url")
                ->label(__('layup::widgets.shared.url'))
                ->url()
                ->nullable(),
            Toggle::make("{$prefix}_new_tab")
                ->label(__('layup::widgets.shared.open_new_tab'))
                ->default(false),
        ];
    }

    /**
     * Two color picker fields.
     *
     * @return array<\Filament\Forms\Components\Component>
     */
    public static function colorPair(string $colorA, string $colorB): array
    {
        return [
            ColorPicker::make("{$colorA}_color")
                ->label(ucfirst(str_replace('_', ' ', $colorA)) . ' color'),
            ColorPicker::make("{$colorB}_color")
                ->label(ucfirst(str_replace('_', ' ', $colorB)) . ' color'),
        ];
    }

    /**
     * Background, hover background, text, and hover text color fields.
     *
     * @return array<\Filament\Forms\Components\Component>
     */
    public static function hoverColors(string $prefix): array
    {
        return [
            ColorPicker::make("{$prefix}_bg_color")
                ->label(__('layup::widgets.shared.background_color')),
            ColorPicker::make("{$prefix}_hover_bg_color")
                ->label('Hover background color'),
            ColorPicker::make("{$prefix}_text_color")
                ->label(__('layup::widgets.shared.text_color')),
            ColorPicker::make("{$prefix}_hover_text_color")
                ->label('Hover text color'),
        ];
    }
}
