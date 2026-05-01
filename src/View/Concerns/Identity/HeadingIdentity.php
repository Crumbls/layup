<?php

declare(strict_types=1);

namespace Crumbls\Layup\View\Concerns\Identity;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

/**
 * Editor-side identity for the heading widget.
 *
 * Used by HeadingWidget (Blade-rendered, default) and may be reused by a
 * downstream HeadingLivewireWidget that wants the same form schema,
 * defaults, label, icon, and preview but renders through a Livewire
 * component. Identity = "what the widget is and how it's edited";
 * rendering tech is orthogonal and lives on the base class.
 */
trait HeadingIdentity
{
    public static function getType(): string
    {
        return 'heading';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.heading');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-h1';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('content')
                ->label(__('layup::widgets.heading.heading_text'))
                ->required(),
            Select::make('level')
                ->label(__('layup::widgets.heading.level'))
                ->options(['h1' => __('layup::widgets.heading.h1'),
                    'h2' => __('layup::widgets.heading.h2'),
                    'h3' => __('layup::widgets.heading.h3'),
                    'h4' => __('layup::widgets.heading.h4'),
                    'h5' => __('layup::widgets.heading.h5'),
                    'h6' => __('layup::widgets.heading.h6'), ])
                ->default('h2'),
            TextInput::make('link_url')
                ->label(__('layup::widgets.heading.link_url'))
                ->url()
                ->placeholder(__('layup::widgets.heading.https'))
                ->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'content' => '',
            'level' => 'h2',
            'link_url' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        $level = strtoupper($data['level'] ?? 'H2');
        $text = $data['content'] ?? '';

        return $text ? "[{$level}] {$text}" : '(empty heading)';
    }
}
