<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TeamGridWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'team-grid';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.team-grid');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-user-group';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('members')
                ->label(__('layup::widgets.team-grid.team_members'))
                ->schema([
                    FileUpload::make('photo')
                        ->label(__('layup::widgets.team-grid.photo'))
                        ->image()
                        ->avatar()
                        ->directory('layup/team'),
                    TextInput::make('name')
                        ->label(__('layup::widgets.team-grid.name'))
                        ->required(),
                    TextInput::make('role')
                        ->label(__('layup::widgets.team-grid.role'))
                        ->nullable(),
                    TextInput::make('linkedin')
                        ->label(__('layup::widgets.team-grid.linkedin_url'))
                        ->url()
                        ->nullable(),
                    TextInput::make('twitter')
                        ->label(__('layup::widgets.team-grid.twitter_url'))
                        ->url()
                        ->nullable(),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
            Select::make('columns')
                ->label(__('layup::widgets.team-grid.columns'))
                ->options(['2' => __('layup::widgets.team-grid.2'), '3' => __('layup::widgets.team-grid.3'), '4' => __('layup::widgets.team-grid.4'), '5' => __('layup::widgets.team-grid.5')])
                ->default('3'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'members' => [],
            'columns' => '3',
        ];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['members'] ?? []);

        return "👥 Team Grid ({$count} members)";
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
