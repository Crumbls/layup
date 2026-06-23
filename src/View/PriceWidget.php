<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class PriceWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'price';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.price');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-currency-dollar';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('amount')->label(__('layup::widgets.price.amount'))->required()->placeholder(__('layup::widgets.price.49')),
            TextInput::make('currency_symbol')->label(__('layup::widgets.price.currency_symbol'))->default('$'),
            TextInput::make('period')->label(__('layup::widgets.price.period'))->placeholder(__('layup::widgets.price.month'))->nullable(),
            TextInput::make('original_amount')->label(__('layup::widgets.price.original_price_strikethrough'))->nullable(),
            TextInput::make('label')->label(__('layup::widgets.price.label'))->placeholder(__('layup::widgets.price.starting_at'))->nullable(),
            Select::make('size')->label(__('layup::widgets.price.size'))->options(['sm' => __('layup::widgets.price.small'), 'md' => __('layup::widgets.price.medium'), 'lg' => __('layup::widgets.price.large'), 'xl' => __('layup::widgets.price.extra_large')])->default('lg'),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['amount' => '', 'currency_symbol' => '$', 'period' => '', 'original_amount' => '', 'label' => '', 'size' => 'lg'];
    }

    public static function getPreview(array $data): string
    {
        return ($data['currency_symbol'] ?? '$') . ($data['amount'] ?? '0') . ($data['period'] ?? '');
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
