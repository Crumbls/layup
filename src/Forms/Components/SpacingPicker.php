<?php

declare(strict_types=1);

namespace Crumbls\Layup\Forms\Components;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class SpacingPicker
{
    /** Normalize saved values before hydration and CSS generation. */
    public static function normalize(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return ['unit' => 'px', 'all' => $value];
        }
        if (is_string($value) && preg_match('/^[pm]-(\d+(?:\.\d+)?)$/D', $value, $matches)) {
            return ['unit' => 'rem', 'all' => (float) $matches[1] / 4];
        }
        if (is_string($value) && preg_match('/^(-?\d+(?:\.\d+)?)(px|rem|em|%)$/D', $value, $matches)) {
            return ['unit' => $matches[2], 'all' => $matches[1]];
        }

        return [];
    }

    /**
     * Simple mode: single value applied to all sides.
     */
    public static function simple(string $name = 'padding', string $label = 'Padding'): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->numeric()
            ->minValue($name === 'padding' ? 0 : null)
            ->nullable()
            ->suffix('px');
    }

    /**
     * Advanced mode: per-side inputs with unit selector.
     * Uses native Filament inputs in a box model layout.
     */
    public static function advanced(string $name = 'padding', string $label = 'Padding'): Section
    {
        return Section::make($label)
            ->schema([
                Select::make("{$name}.unit")
                    ->label(__('layup::widgets.shared.unit'))
                    ->options([
                        'px' => 'px',
                        'rem' => 'rem',
                        'em' => 'em',
                        '%' => '%',
                    ])
                    ->default('px')
                    ->selectablePlaceholder(false)
                    ->columnSpan(2),

                Grid::make(1)
                    ->schema([
                        TextInput::make("{$name}.top")
                            ->label(__('layup::widgets.shared.top'))
                            ->numeric()
                            ->minValue($name === 'padding' ? 0 : null)
                            ->nullable()
                            ->placeholder('0'),
                    ])
                    ->columnSpan(2)
                    ->extraAttributes(['class' => 'lyp-spacing-box-top']),

                Grid::make(3)
                    ->schema([
                        TextInput::make("{$name}.left")
                            ->label(__('layup::widgets.shared.left'))
                            ->numeric()
                            ->minValue($name === 'padding' ? 0 : null)
                            ->nullable()
                            ->placeholder('0'),
                        TextInput::make("{$name}.all")
                            ->label(__('layup::builder.all_sides'))
                            ->numeric()
                            ->minValue($name === 'padding' ? 0 : null)
                            ->nullable()
                            ->placeholder('0')
                            ->extraInputAttributes(['class' => 'text-center']),
                        TextInput::make("{$name}.right")
                            ->label(__('layup::widgets.shared.right'))
                            ->numeric()
                            ->minValue($name === 'padding' ? 0 : null)
                            ->nullable()
                            ->placeholder('0'),
                    ])
                    ->columnSpan(2)
                    ->extraAttributes(['class' => 'lyp-spacing-box-middle']),

                Grid::make(1)
                    ->schema([
                        TextInput::make("{$name}.bottom")
                            ->label(__('layup::widgets.shared.bottom'))
                            ->numeric()
                            ->minValue($name === 'padding' ? 0 : null)
                            ->nullable()
                            ->placeholder('0'),
                    ])
                    ->columnSpan(2)
                    ->extraAttributes(['class' => 'lyp-spacing-box-bottom']),
            ])
            ->columns(2)
            ->collapsed()
            ->compact();
    }
}
