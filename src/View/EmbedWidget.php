<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class EmbedWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'embed';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.embed');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-globe-alt';
    }

    public static function getCategory(): string
    {
        return 'advanced';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Textarea::make('html')
                ->label(__('layup::widgets.embed.embed_code'))
                ->helperText(__('layup::widgets.embed.paste_embed_html_iframe_script_etc'))
                ->rows(6)
                ->columnSpanFull(),
            Select::make('aspect')
                ->label(__('layup::widgets.embed.aspect_ratio'))
                ->options(['' => __('layup::widgets.embed.auto'),
                    '16/9' => __('layup::widgets.embed.16_9'),
                    '4/3' => __('layup::widgets.embed.4_3'),
                    '1/1' => __('layup::widgets.embed.1_1'),
                    '21/9' => __('layup::widgets.embed.21_9'), ])
                ->default('')
                ->nullable(),
            TextInput::make('max_width')
                ->label(__('layup::widgets.embed.max_width'))
                ->placeholder(__('layup::widgets.embed.e_g_600px'))
                ->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'html' => '',
            'aspect' => '',
            'max_width' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        if (empty($data['html'])) {
            return '(no embed code)';
        }

        return 'Embedded content';
    }
}
