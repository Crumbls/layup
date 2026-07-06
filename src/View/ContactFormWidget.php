<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ContactFormWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'contact-form';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.contact-form');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-envelope';
    }

    public static function getCategory(): string
    {
        return 'interactive';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('action')
                ->label(__('layup::widgets.contact-form.form_action_url'))
                ->helperText(__('layup::widgets.contact-form.where_the_form_submits_e_g_contact_formspree_url_m'))
                ->required(),
            TextInput::make('submit_text')
                ->label(__('layup::widgets.contact-form.submit_button_text'))
                ->default('Send Message'),
            TextInput::make('success_message')
                ->label(__('layup::widgets.contact-form.success_message'))
                ->default('Thank you! Your message has been sent.'),
            Repeater::make('fields')
                ->label(__('layup::widgets.contact-form.form_fields'))
                ->schema([
                    TextInput::make('label')
                        ->label(__('layup::widgets.contact-form.label'))
                        ->required(),
                    TextInput::make('name')
                        ->label(__('layup::widgets.contact-form.field_name'))
                        ->required(),
                    Select::make('type')
                        ->label(__('layup::widgets.contact-form.type'))
                        ->options(['text' => __('layup::widgets.contact-form.text'),
                            'email' => __('layup::widgets.contact-form.email'),
                            'tel' => __('layup::widgets.contact-form.phone'),
                            'textarea' => __('layup::widgets.contact-form.text_area'),
                            'select' => __('layup::widgets.contact-form.dropdown'), ])
                        ->default('text')
                        ->required(),
                    Toggle::make('required')
                        ->label(__('layup::widgets.contact-form.required'))
                        ->default(false),
                    TextInput::make('placeholder')
                        ->label(__('layup::widgets.contact-form.placeholder'))
                        ->nullable(),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'action' => '/contact',
            'submit_text' => 'Send Message',
            'success_message' => 'Thank you! Your message has been sent.',
            'fields' => [
                ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'required' => true, 'placeholder' => 'Your name'],
                ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'required' => true, 'placeholder' => 'your@email.com'],
                ['label' => 'Message', 'name' => 'message', 'type' => 'textarea', 'required' => true, 'placeholder' => 'How can we help?'],
            ],
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['fields'] ?? []);

        return "Contact Form ({$count} fields)";
    }
}
