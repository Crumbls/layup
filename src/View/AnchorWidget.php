<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class AnchorWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'anchor';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.anchor');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-link';
    }

    public static function getCategory(): string
    {
        return 'layout';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('anchor_id')
                ->label(__('layup::widgets.anchor.anchor_id'))
                ->helperText(__('layup::widgets.anchor.use_this_id_in_urls_or_links_to_jump_here'))
                ->required(),
            TextInput::make('offset')
                ->label(__('layup::widgets.anchor.scroll_offset_px'))
                ->helperText(__('layup::widgets.anchor.negative_value_scrolls_above_this_point_useful_for'))
                ->numeric()
                ->default(0),
            Toggle::make('invisible')
                ->label(__('layup::widgets.anchor.invisible_no_visual_output'))
                ->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'anchor_id' => '',
            'offset' => 0,
            'invisible' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        return '#' . ($data['anchor_id'] ?? '');
    }
}
