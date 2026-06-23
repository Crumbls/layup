<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class CodeWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'code';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.code');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-code-bracket';
    }

    public static function getCategory(): string
    {
        return 'advanced';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Textarea::make('code')
                ->label(__('layup::widgets.code.code'))
                ->rows(10)
                ->columnSpanFull()
                ->required(),
            Select::make('language')
                ->label(__('layup::widgets.code.language'))
                ->options(['plaintext' => __('layup::widgets.code.plain_text'),
                    'html' => __('layup::widgets.code.html'),
                    'css' => __('layup::widgets.code.css'),
                    'javascript' => __('layup::widgets.code.javascript'),
                    'php' => __('layup::widgets.code.php'),
                    'python' => __('layup::widgets.code.python'),
                    'ruby' => __('layup::widgets.code.ruby'),
                    'json' => __('layup::widgets.code.json'),
                    'yaml' => __('layup::widgets.code.yaml'),
                    'bash' => __('layup::widgets.code.bash'),
                    'sql' => __('layup::widgets.code.sql'),
                    'markdown' => __('layup::widgets.code.markdown'), ])
                ->default('plaintext'),
            TextInput::make('filename')
                ->label(__('layup::widgets.code.filename'))
                ->placeholder(__('layup::widgets.code.e_g_example_php'))
                ->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'code' => '',
            'language' => 'plaintext',
            'filename' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        $lang = $data['language'] ?? 'plaintext';
        $lines = substr_count($data['code'] ?? '', "\n") + 1;

        return "💻 {$lang} ({$lines} lines)";
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
