<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class AnimatedHeadingWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'animated-heading';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.animated-heading');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-sparkles';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('before_text')
                ->label(__('layup::widgets.animated-heading.before_text'))
                ->nullable(),
            TextInput::make('animated_text')
                ->label(__('layup::widgets.animated-heading.animated_text'))
                ->required(),
            TextInput::make('after_text')
                ->label(__('layup::widgets.animated-heading.after_text'))
                ->nullable(),
            Select::make('tag')
                ->label(__('layup::widgets.animated-heading.tag'))
                ->options(['h1' => __('layup::widgets.animated-heading.h1'), 'h2' => __('layup::widgets.animated-heading.h2'), 'h3' => __('layup::widgets.animated-heading.h3'), 'h4' => __('layup::widgets.animated-heading.h4')])
                ->default('h2'),
            Select::make('effect')
                ->label(__('layup::widgets.animated-heading.effect'))
                ->options(['highlight' => __('layup::widgets.animated-heading.highlight'),
                    'underline' => __('layup::widgets.animated-heading.underline'),
                    'circle' => __('layup::widgets.animated-heading.circle'),
                    'strikethrough' => __('layup::widgets.animated-heading.strikethrough'), ])
                ->default('highlight'),
            ColorPicker::make('accent_color')
                ->label(__('layup::widgets.animated-heading.accent_color'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'before_text' => '',
            'animated_text' => '',
            'after_text' => '',
            'tag' => 'h2',
            'effect' => 'highlight',
            'accent_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        return '✨ ' . ($data['before_text'] ?? '') . ' [' . ($data['animated_text'] ?? '') . '] ' . ($data['after_text'] ?? '');
    }
}
