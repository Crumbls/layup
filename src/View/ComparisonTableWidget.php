<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class ComparisonTableWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'comparison-table';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.comparison-table');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-scale';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('column_a')
                ->label(__('layup::widgets.comparison-table.column_a_header'))
                ->default('Us')
                ->required(),
            TextInput::make('column_b')
                ->label(__('layup::widgets.comparison-table.column_b_header'))
                ->default('Them')
                ->required(),
            Repeater::make('rows')
                ->label(__('layup::widgets.comparison-table.comparison_rows'))
                ->schema([
                    TextInput::make('feature')
                        ->label(__('layup::widgets.comparison-table.feature'))
                        ->required(),
                    TextInput::make('value_a')
                        ->label(__('layup::widgets.comparison-table.column_a_value'))
                        ->default('✓'),
                    TextInput::make('value_b')
                        ->label(__('layup::widgets.comparison-table.column_b_value'))
                        ->default('✗'),
                ])
                ->defaultItems(5)
                ->columnSpanFull(),
            ColorPicker::make('highlight_color')
                ->label(__('layup::widgets.comparison-table.highlight_color_column_a'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'column_a' => 'Us',
            'column_b' => 'Them',
            'rows' => [],
            'highlight_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        $a = $data['column_a'] ?? 'A';
        $b = $data['column_b'] ?? 'B';

        return "⚖️ {$a} vs {$b}";
    }
}
