<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ImageTextWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'image-text';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.image-text');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-newspaper';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            FileUpload::make('image')->label(__('layup::widgets.image-text.image'))->image()->directory('layup/image-text'),
            RichEditor::make('content')->label(__('layup::widgets.image-text.content'))->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'h2', 'h3'])->columnSpanFull(),
            TextInput::make('heading')->label(__('layup::widgets.image-text.heading'))->nullable(),
            Select::make('image_position')->label(__('layup::widgets.image-text.image_position'))->options(['left' => __('layup::widgets.image-text.left'), 'right' => __('layup::widgets.image-text.right')])->default('left'),
            Select::make('image_width')->label(__('layup::widgets.image-text.image_width'))->options(['1/3' => __('layup::widgets.image-text.33'), '1/2' => __('layup::widgets.image-text.50'), '2/5' => __('layup::widgets.image-text.40'), '3/5' => __('layup::widgets.image-text.60')])->default('1/2'),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['image' => '', 'content' => '', 'heading' => '', 'image_position' => 'left', 'image_width' => '1/2'];
    }

    public static function getPreview(array $data): string
    {
        return '' . ($data['heading'] ?? 'Image + Text');
    }
}
