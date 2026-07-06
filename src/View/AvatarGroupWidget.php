<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class AvatarGroupWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'avatar-group';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.avatar-group');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-users';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            FileUpload::make('avatars')
                ->label(__('layup::widgets.avatar-group.avatars'))
                ->image()
                ->avatar()
                ->multiple()
                ->reorderable()
                ->directory('layup/avatars')
                ->columnSpanFull(),
            TextInput::make('extra_count')
                ->label(__('layup::widgets.avatar-group.extra_count_e_g_12'))
                ->placeholder(__('layup::widgets.avatar-group.12'))
                ->nullable(),
            TextInput::make('label')
                ->label(__('layup::widgets.avatar-group.label'))
                ->placeholder(__('layup::widgets.avatar-group.join_1_200_users'))
                ->nullable(),
            Select::make('size')
                ->label(__('layup::widgets.avatar-group.size'))
                ->options(['sm' => __('layup::widgets.avatar-group.small_32px'), 'md' => __('layup::widgets.avatar-group.medium_40px'), 'lg' => __('layup::widgets.avatar-group.large_48px')])
                ->default('md'),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['avatars' => [], 'extra_count' => '', 'label' => '', 'size' => 'md'];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['avatars'] ?? []);

        return "{$count} avatars" . (empty($data['extra_count']) ? '' : " {$data['extra_count']}");
    }
}
