<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;

class CtaBannerWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'cta-banner';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.cta-banner');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-megaphone';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('heading')->label(__('layup::widgets.cta-banner.heading'))->required(),
            TextInput::make('subtitle')->label(__('layup::widgets.cta-banner.subtitle'))->nullable(),
            TextInput::make('button_text')->label(__('layup::widgets.cta-banner.button_text'))->default('Get Started'),
            TextInput::make('button_url')->label(__('layup::widgets.cta-banner.button_url'))->url()->default('#'),
            ColorPicker::make('bg_color')->label(__('layup::widgets.cta-banner.background_color'))->default(null),
            ColorPicker::make('text_color_banner')->label(__('layup::widgets.cta-banner.text_color')),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['heading' => '', 'subtitle' => '', 'button_text' => 'Get Started', 'button_url' => '#', 'bg_color' => null, 'text_color_banner' => null];
    }

    public static function getPreview(array $data): string
    {
        return '📢 ' . ($data['heading'] ?? 'CTA Banner');
    }
}
