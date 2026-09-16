<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Contracts\View\View;

class Row extends BaseView
{
    public const GAPS = ['gap-0', 'gap-2', 'gap-4', 'gap-6', 'gap-8', 'gap-12'];

    /**
     * @param  array<Column>  $columns
     */
    public static function make(array $data = [], array $children = []): static
    {
        return new static($data, $children);
    }

    /**
     * Content tab: flex direction, justify, align, gap, wrap.
     */
    public static function getContentFormSchema(): array
    {
        return [
            Select::make('layout')
                ->label(__('layup::widgets.row.layout'))
                ->options(['flex' => 'Flex', 'grid' => 'Grid'])
                ->default('flex')
                ->selectablePlaceholder(false)
                ->live(),
            Select::make('gap')
                ->label(__('layup::widgets.row.gap'))
                ->options([
                    'gap-0' => '0',
                    'gap-2' => '0.5rem',
                    'gap-4' => '1rem',
                    'gap-6' => '1.5rem',
                    'gap-8' => '2rem',
                    'gap-12' => '3rem',
                ])
                ->default('gap-4')
                ->selectablePlaceholder(false)
                ->visible(fn (Get $get): bool => $get('layout') === 'grid'),
            Select::make('direction')
                ->label(__('layup::widgets.row.direction'))
                ->options([
                    'row' => __('layup::widgets.row.row_horizontal'),
                    'column' => __('layup::widgets.row.column_vertical'),
                    'row-reverse' => __('layup::widgets.row.row_reverse'),
                    'column-reverse' => __('layup::widgets.row.column_reverse'),
                ])
                ->default('row')
                ->visible(fn (Get $get): bool => $get('layout') !== 'grid'),
            Select::make('justify')
                ->label(__('layup::widgets.row.justify_content'))
                ->options([
                    'start' => __('layup::widgets.row.start'),
                    'center' => __('layup::widgets.row.center'),
                    'end' => __('layup::widgets.row.end'),
                    'between' => __('layup::widgets.row.space_between'),
                    'around' => __('layup::widgets.row.space_around'),
                    'evenly' => __('layup::widgets.row.space_evenly'),
                ])
                ->default('start')
                ->visible(fn (Get $get): bool => $get('layout') !== 'grid'),
            Select::make('align')
                ->label(__('layup::widgets.row.align_items'))
                ->options([
                    'start' => __('layup::widgets.row.start'),
                    'center' => __('layup::widgets.row.center'),
                    'end' => __('layup::widgets.row.end'),
                    'stretch' => __('layup::widgets.row.stretch'),
                    'baseline' => __('layup::widgets.row.baseline'),
                ])
                ->default('stretch'),
            Select::make('wrap')
                ->label(__('layup::widgets.row.wrap'))
                ->options([
                    'nowrap' => __('layup::widgets.row.no_wrap'),
                    'wrap' => __('layup::widgets.row.wrap_option'),
                    'wrap-reverse' => __('layup::widgets.row.wrap_reverse'),
                ])
                ->default('wrap')
                ->visible(fn (Get $get): bool => $get('layout') !== 'grid'),
            Toggle::make('full_width')
                ->label(__('layup::widgets.row.full_width'))
                ->helperText(__('layup::widgets.row.full_width_helper'))
                ->default(false),
        ];
    }

    protected static function withLiveValidation(array $components): array
    {
        $components = parent::withLiveValidation($components);

        foreach ($components as $component) {
            if ($component instanceof Select && $component->getName() === 'layout') {
                $component->live();
            }
        }

        return $components;
    }

    public function render(): View
    {
        return view('layup::components.row', [
            'children' => $this->children,
            'data' => $this->data,
        ]);
    }
}
