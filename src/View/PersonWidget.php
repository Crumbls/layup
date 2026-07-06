<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

class PersonWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'person';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.person');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-user-circle';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label(__('layup::widgets.person.name'))
                ->required(),
            TextInput::make('role')
                ->label(__('layup::widgets.person.role_position'))
                ->nullable(),
            FileUpload::make('photo')
                ->label(__('layup::widgets.person.photo'))
                ->image()
                ->directory('layup/people'),
            RichEditor::make('bio')
                ->label(__('layup::widgets.person.bio'))
                ->columnSpanFull(),
            TextInput::make('email')
                ->label(__('layup::widgets.person.email'))
                ->email()
                ->nullable(),
            TextInput::make('website')
                ->label(__('layup::widgets.person.website'))
                ->url()
                ->nullable(),
            TextInput::make('facebook')
                ->label(__('layup::widgets.person.facebook_url'))
                ->url()
                ->nullable(),
            TextInput::make('twitter')
                ->label(__('layup::widgets.person.x_twitter_url'))
                ->url()
                ->nullable(),
            TextInput::make('linkedin')
                ->label(__('layup::widgets.person.linkedin_url'))
                ->url()
                ->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'name' => '',
            'role' => '',
            'photo' => '',
            'bio' => '',
            'email' => '',
            'website' => '',
            'facebook' => '',
            'twitter' => '',
            'linkedin' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        $name = $data['name'] ?? '';
        $role = $data['role'] ?? '';

        return $name ? "{$name}" . ($role ? " — {$role}" : '') : '(empty person)';
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
