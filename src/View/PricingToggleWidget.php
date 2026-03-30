<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class PricingToggleWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'pricing-toggle';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.pricing-toggle');
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
            TextInput::make('monthly_label')
                ->label(__('layup::widgets.pricing-toggle.monthly_label'))
                ->default('Monthly'),
            TextInput::make('annual_label')
                ->label(__('layup::widgets.pricing-toggle.annual_label'))
                ->default('Annual'),
            TextInput::make('discount_badge')
                ->label(__('layup::widgets.pricing-toggle.discount_badge'))
                ->placeholder(__('layup::widgets.pricing-toggle.save_20'))
                ->nullable(),
            Repeater::make('plans')
                ->label(__('layup::widgets.pricing-toggle.plans'))
                ->schema([
                    TextInput::make('name')
                        ->label(__('layup::widgets.pricing-toggle.plan_name'))
                        ->required(),
                    TextInput::make('monthly_price')
                        ->label(__('layup::widgets.pricing-toggle.monthly_price'))
                        ->required(),
                    TextInput::make('annual_price')
                        ->label(__('layup::widgets.pricing-toggle.annual_price'))
                        ->required(),
                    TextInput::make('features')
                        ->label(__('layup::widgets.pricing-toggle.features_comma_separated'))
                        ->nullable(),
                    TextInput::make('cta_text')
                        ->label(__('layup::widgets.pricing-toggle.cta_text'))
                        ->default('Get Started'),
                    TextInput::make('cta_url')
                        ->label(__('layup::widgets.pricing-toggle.cta_url'))
                        ->url()
                        ->nullable(),
                    Toggle::make('featured')
                        ->label(__('layup::widgets.pricing-toggle.featured_popular'))
                        ->default(false),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            ColorPicker::make('accent_color')
                ->label(__('layup::widgets.pricing-toggle.accent_color'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'monthly_label' => 'Monthly',
            'annual_label' => 'Annual',
            'discount_badge' => 'Save 20%',
            'plans' => [],
            'accent_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['plans'] ?? []);

        return "💰 Pricing Toggle ({$count} plans)";
    }
}
