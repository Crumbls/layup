<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class LoginWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'login';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.login');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-lock-closed';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('action')
                ->label(__('layup::widgets.login.form_action_url'))
                ->default('/login')
                ->required(),
            TextInput::make('title')
                ->label(__('layup::widgets.login.title'))
                ->default('Sign In'),
            TextInput::make('email_label')
                ->label(__('layup::widgets.login.email_field_label'))
                ->default('Email'),
            TextInput::make('password_label')
                ->label(__('layup::widgets.login.password_field_label'))
                ->default('Password'),
            TextInput::make('submit_text')
                ->label(__('layup::widgets.login.submit_button_text'))
                ->default('Sign In'),
            TextInput::make('forgot_url')
                ->label(__('layup::widgets.login.forgot_password_url'))
                ->nullable(),
            TextInput::make('register_url')
                ->label(__('layup::widgets.login.register_url'))
                ->nullable(),
            Toggle::make('remember_me')
                ->label(__('layup::widgets.login.show_remember_me'))
                ->default(true),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'action' => '/login',
            'title' => 'Sign In',
            'email_label' => 'Email',
            'password_label' => 'Password',
            'submit_text' => 'Sign In',
            'forgot_url' => '',
            'register_url' => '',
            'remember_me' => true,
        ];
    }

    public static function getPreview(array $data): string
    {
        return 'Login Form';
    }
}
