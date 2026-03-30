<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CookieConsentWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'cookie-consent';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.cookie-consent');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-shield-check';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('message')
                ->label(__('layup::widgets.cookie-consent.message'))
                ->default('We use cookies to enhance your experience. By continuing, you agree to our use of cookies.')
                ->required()
                ->columnSpanFull(),
            TextInput::make('accept_text')
                ->label(__('layup::widgets.cookie-consent.accept_button_text'))
                ->default('Accept'),
            TextInput::make('decline_text')
                ->label(__('layup::widgets.cookie-consent.decline_button_text'))
                ->default('Decline'),
            TextInput::make('policy_url')
                ->label(__('layup::widgets.cookie-consent.privacy_policy_url'))
                ->url()
                ->nullable(),
            TextInput::make('policy_text')
                ->label(__('layup::widgets.cookie-consent.policy_link_text'))
                ->default('Privacy Policy'),
            ColorPicker::make('bg_color')
                ->label(__('layup::widgets.cookie-consent.background_color')),
            Select::make('position')
                ->label(__('layup::widgets.cookie-consent.position'))
                ->options(['bottom' => __('layup::widgets.cookie-consent.bottom'),
                    'top' => __('layup::widgets.cookie-consent.top'), ])
                ->default('bottom'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'message' => 'We use cookies to enhance your experience. By continuing, you agree to our use of cookies.',
            'accept_text' => 'Accept',
            'decline_text' => 'Decline',
            'policy_url' => '',
            'policy_text' => 'Privacy Policy',
            'bg_color' => null,
            'position' => 'bottom',
        ];
    }

    public static function getPreview(array $data): string
    {
        return '🍪 Cookie Consent Banner';
    }
}
