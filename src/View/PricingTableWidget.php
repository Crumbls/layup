<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class PricingTableWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'pricing-table';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.pricing-table');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-currency-dollar';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('layup::widgets.pricing-table.plan_name'))
                ->required(),
            TextInput::make('subtitle')
                ->label(__('layup::widgets.pricing-table.subtitle'))
                ->nullable(),
            TextInput::make('price')
                ->label(__('layup::widgets.pricing-table.price'))
                ->required()
                ->placeholder(__('layup::widgets.pricing-table.49')),
            TextInput::make('currency')
                ->label(__('layup::widgets.pricing-table.currency_symbol'))
                ->default('$'),
            Select::make('period')
                ->label(__('layup::widgets.pricing-table.billing_period'))
                ->options(['month' => __('layup::widgets.pricing-table.per_month'),
                    'year' => __('layup::widgets.pricing-table.per_year'),
                    'once' => __('layup::widgets.pricing-table.one_time'),
                    'custom' => __('layup::widgets.pricing-table.custom'), ])
                ->default('month'),
            TextInput::make('period_custom')
                ->label(__('layup::widgets.pricing-table.custom_period_text'))
                ->visible(fn (callable $get): bool => $get('period') === 'custom')
                ->nullable(),
            Repeater::make('features')
                ->label(__('layup::widgets.pricing-table.features'))
                ->schema([
                    TextInput::make('text')
                        ->label(__('layup::widgets.pricing-table.feature'))
                        ->required(),
                    Toggle::make('included')
                        ->label(__('layup::widgets.pricing-table.included'))
                        ->default(true),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            TextInput::make('button_text')
                ->label(__('layup::widgets.pricing-table.button_text'))
                ->default('Get Started'),
            TextInput::make('button_url')
                ->label(__('layup::widgets.pricing-table.button_url'))
                ->url(),
            Toggle::make('featured')
                ->label(__('layup::widgets.pricing-table.featured_highlighted'))
                ->default(false),
            TextInput::make('badge_text')
                ->label(__('layup::widgets.pricing-table.badge_text'))
                ->placeholder(__('layup::widgets.pricing-table.popular'))
                ->default('Popular')
                ->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'title' => '',
            'subtitle' => '',
            'price' => '',
            'currency' => '$',
            'period' => 'month',
            'period_custom' => '',
            'features' => [],
            'button_text' => 'Get Started',
            'button_url' => '#',
            'featured' => false,
            'badge_text' => 'Popular',
        ];
    }

    public static function getPreview(array $data): string
    {
        $title = $data['title'] ?? '';
        $price = ($data['currency'] ?? '$') . ($data['price'] ?? '');

        return $title ? "{$title} · {$price}" : '(empty pricing table)';
    }
}
