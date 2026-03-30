<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class SkillBarWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'skill-bar';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.skill-bar');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-adjustments-horizontal';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Repeater::make('skills')
                ->label(__('layup::widgets.skill-bar.skills'))
                ->schema([
                    TextInput::make('name')->label(__('layup::widgets.skill-bar.skill_name'))->required(),
                    TextInput::make('percent')->label(__('layup::widgets.skill-bar.percentage'))->numeric()->minValue(0)->maxValue(100)->required(),
                    ColorPicker::make('color')->label(__('layup::widgets.skill-bar.bar_color')),
                ])
                ->defaultItems(3)
                ->columnSpanFull(),
        ];
    }

    public static function getDefaultData(): array
    {
        return ['skills' => [
            ['name' => 'PHP', 'percent' => 90, 'color' => '#3b82f6'],
            ['name' => 'JavaScript', 'percent' => 75, 'color' => '#f59e0b'],
            ['name' => 'Laravel', 'percent' => 95, 'color' => '#ef4444'],
        ]];
    }

    public static function getPreview(array $data): string
    {
        $count = count($data['skills'] ?? []);

        return "📊 Skill Bars ({$count})";
    }
}
