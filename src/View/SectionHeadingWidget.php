<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class SectionHeadingWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'section-heading';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.section-heading');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-bars-3';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('heading')->label(__('layup::widgets.section-heading.heading'))->required(),
            TextInput::make('subtitle')->label(__('layup::widgets.section-heading.subtitle'))->nullable(),
            Select::make('alignment')->label(__('layup::widgets.section-heading.alignment'))->options(['left' => __('layup::widgets.section-heading.left'), 'center' => __('layup::widgets.section-heading.center'), 'right' => __('layup::widgets.section-heading.right')])->default('center'),
            Toggle::make('show_divider')->label(__('layup::widgets.section-heading.show_divider'))->default(true),
            ColorPicker::make('divider_color')->label(__('layup::widgets.section-heading.divider_color'))->default(null),
            Select::make('heading_tag')->label(__('layup::widgets.section-heading.heading_tag'))->options(['h1' => __('layup::widgets.section-heading.h1'), 'h2' => __('layup::widgets.section-heading.h2'), 'h3' => __('layup::widgets.section-heading.h3')])->default('h2'),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['heading' => '', 'subtitle' => '', 'alignment' => 'center', 'show_divider' => true, 'divider_color' => null, 'heading_tag' => 'h2'];
    }

    public static function getPreview(array $data): string
    {
        return '📌 ' . ($data['heading'] ?? 'Section Heading');
    }
}
