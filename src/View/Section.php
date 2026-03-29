<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;

class Section extends BaseView
{
    /**
     * Section wraps one or more rows and provides background styling.
     * Content structure: { sections: [{ id, settings, rows: [...] }] }
     */
    public static function getFormSchema(?string $statePath = null): array
    {
        return static::withUploadDisk([
            Tabs::make('section_settings')
                ->tabs([
                    Tabs\Tab::make(__('layup::widgets.shared.tab_content'))
                        ->schema(static::withLiveValidation(static::getContentFormSchema()))
                        ->columns(2),
                    Tabs\Tab::make(__('layup::widgets.shared.tab_design'))
                        ->schema(static::withLiveValidation(static::getDesignFormSchema()))
                        ->columns(2),
                    Tabs\Tab::make(__('layup::widgets.shared.tab_advanced'))
                        ->schema(static::withLiveValidation(static::getAdvancedFormSchema()))
                        ->columns(2),
                ]),
        ]);
    }

    public static function getContentFormSchema(): array
    {
        return [
            FileUpload::make('background_image')
                ->label(__('layup::widgets.section.background_image'))
                ->image()
                ->directory('layup/sections'),
            TextInput::make('background_video')
                ->label(__('layup::widgets.section.background_video'))
                ->url()
                ->placeholder(__('layup::widgets.section.background_video_placeholder'))
                ->nullable(),
            TextInput::make('background_gradient')
                ->label(__('layup::widgets.section.background_gradient'))
                ->placeholder(__('layup::widgets.section.background_gradient_placeholder'))
                ->nullable(),
            ColorPicker::make('overlay_color')
                ->label(__('layup::widgets.section.overlay_color'))
                ->default('#000000'),
            TextInput::make('overlay_opacity')
                ->label(__('layup::widgets.section.overlay_opacity'))
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(0),
            Toggle::make('parallax')
                ->label(__('layup::widgets.section.parallax'))
                ->default(false),
            Select::make('min_height')
                ->label(__('layup::widgets.section.min_height'))
                ->options([
                    '' => __('layup::widgets.section.auto'),
                    '50vh' => __('layup::widgets.section.half_screen'),
                    '75vh' => __('layup::widgets.section.three_quarter_screen'),
                    '100vh' => __('layup::widgets.section.full_screen'),
                ])
                ->default(''),
            Toggle::make('full_width')
                ->label(__('layup::widgets.section.full_width'))
                ->default(false),
        ];
    }

    public static function getDesignFormSchema(): array
    {
        return parent::getDesignFormSchema();
    }

    public static function getAdvancedFormSchema(): array
    {
        return parent::getAdvancedFormSchema();
    }

    /**
     * Build inline styles for section wrapper.
     */
    public static function buildSectionStyles(array $settings): string
    {
        $styles = [];

        if (! empty($settings['background_color'])) {
            $styles[] = "background-color: {$settings['background_color']}";
        }

        if (! empty($settings['background_gradient'])) {
            $styles[] = "background: {$settings['background_gradient']}";
        }

        if (! empty($settings['background_image']) && empty($settings['background_video'])) {
            $url = asset('storage/' . $settings['background_image']);
            $styles[] = "background-image: url('{$url}')";
            $styles[] = 'background-size: cover';
            $styles[] = 'background-position: center';
            if (! empty($settings['parallax'])) {
                $styles[] = 'background-attachment: fixed';
            }
        }

        if (! empty($settings['min_height'])) {
            $styles[] = "min-height: {$settings['min_height']}";
        }

        if (! empty($settings['inline_css'])) {
            $styles[] = $settings['inline_css'];
        }

        return implode('; ', $styles);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('layup::components.section');
    }
}
