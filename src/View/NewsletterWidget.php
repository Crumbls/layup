<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class NewsletterWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'newsletter';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.newsletter');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-envelope-open';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('heading')
                ->label(__('layup::widgets.newsletter.heading'))
                ->default('Stay in the loop'),
            TextInput::make('description')
                ->label(__('layup::widgets.newsletter.description'))
                ->default('Get the latest updates delivered to your inbox.')
                ->nullable(),
            TextInput::make('action')
                ->label(__('layup::widgets.newsletter.form_action_url'))
                ->helperText(__('layup::widgets.newsletter.mailchimp_convertkit_or_custom_endpoint'))
                ->required(),
            TextInput::make('placeholder')
                ->label(__('layup::widgets.newsletter.email_placeholder'))
                ->default('Enter your email'),
            TextInput::make('submit_text')
                ->label(__('layup::widgets.newsletter.button_text'))
                ->default('Subscribe'),
            TextInput::make('success_message')
                ->label(__('layup::widgets.newsletter.success_message'))
                ->default("You're in! Check your inbox."),
            Select::make('layout')
                ->label(__('layup::widgets.newsletter.layout'))
                ->options(['inline' => __('layup::widgets.newsletter.inline_side_by_side'),
                    'stacked' => __('layup::widgets.newsletter.stacked'), ])
                ->default('inline'),
            ColorPicker::make('button_color')
                ->label(__('layup::widgets.newsletter.button_color'))
                ->default(null),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'heading' => 'Stay in the loop',
            'description' => 'Get the latest updates delivered to your inbox.',
            'action' => '',
            'placeholder' => 'Enter your email',
            'submit_text' => 'Subscribe',
            'success_message' => "You're in! Check your inbox.",
            'layout' => 'inline',
            'button_color' => null,
        ];
    }

    public static function getPreview(array $data): string
    {
        return '📬 Newsletter Signup';
    }
}
