<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ChangelogWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'changelog';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.changelog');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-document-text';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('releases')
                ->label(__('layup::widgets.changelog.releases'))
                ->schema([
                    TextInput::make('version')->label(__('layup::widgets.changelog.version'))->required(),
                    TextInput::make('date')->label(__('layup::widgets.changelog.date'))->required(),
                    Select::make('type')->label(__('layup::widgets.changelog.type'))->options(['major' => __('layup::widgets.changelog.major'), 'minor' => __('layup::widgets.changelog.minor'), 'patch' => __('layup::widgets.changelog.patch')])->default('minor'),
                    Textarea::make('changes')->label(__('layup::widgets.changelog.changes_one_per_line'))->rows(4)->required(),
                ])
                ->defaultItems(2)
                ->columnSpanFull(),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['releases' => []];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['releases'] ?? []);

        return "Changelog ({$count} releases)";
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
