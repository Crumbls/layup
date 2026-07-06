<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class AlertWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'alert';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.alert');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-exclamation-triangle';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Select::make('type')
                ->label(__('layup::widgets.alert.type'))
                ->options(['info' => __('layup::widgets.alert.info'),
                    'success' => __('layup::widgets.alert.success'),
                    'warning' => __('layup::widgets.alert.warning'),
                    'danger' => __('layup::widgets.alert.danger'), ])
                ->default('info'),
            TextInput::make('title')
                ->label(__('layup::widgets.alert.title'))
                ->nullable(),
            RichEditor::make('content')
                ->label(__('layup::widgets.alert.content'))
                ->toolbarButtons(['bold', 'italic', 'link', 'bulletList'])
                ->columnSpanFull(),
            Toggle::make('dismissible')
                ->label(__('layup::widgets.alert.dismissible'))
                ->default(false),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'type' => 'info',
            'title' => '',
            'content' => '',
            'dismissible' => false,
        ];
    }

    public static function getPreview(array $data): string
    {
        $type = strtoupper($data['type'] ?? 'info');
        $title = $data['title'] ?? '';

        return "[{$type}] {$title}";
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
