<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class MarqueeWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'marquee';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.marquee');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-arrow-right';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('text')
                ->label(__('layup::widgets.marquee.text'))
                ->required()
                ->columnSpanFull(),
            Select::make('speed')
                ->label(__('layup::widgets.marquee.speed'))
                ->options(['30' => __('layup::widgets.marquee.slow'),
                    '20' => __('layup::widgets.marquee.normal'),
                    '10' => __('layup::widgets.marquee.fast'),
                    '5' => __('layup::widgets.marquee.very_fast'), ])
                ->default('20'),
            Select::make('direction')
                ->label(__('layup::widgets.marquee.direction'))
                ->options(['left' => __('layup::widgets.marquee.left'),
                    'right' => __('layup::widgets.marquee.right'), ])
                ->default('left'),
            Toggle::make('pause_on_hover')
                ->label(__('layup::widgets.marquee.pause_on_hover'))
                ->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'text' => '',
            'speed' => '20',
            'direction' => 'left',
            'pause_on_hover' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        $text = $data['text'] ?? '';
        $short = mb_strlen($text) > 40 ? mb_substr($text, 0, 40) . '…' : $text;

        return "{$short}";
    }
}
