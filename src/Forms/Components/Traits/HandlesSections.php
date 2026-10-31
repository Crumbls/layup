<?php

declare(strict_types=1);

namespace Crumbls\Layup\Forms\Components\Traits;

use Crumbls\Layup\View\BaseView;
use Crumbls\Layup\View\Section;
use Filament\Actions\Action;
use Filament\Support\Components\Attributes\ExposedLivewireMethod;
use Illuminate\Support\Str;
use Livewire\Attributes\Renderless;

trait HandlesSections
{
    public function sectionEditAction(): Action
    {
        return Action::make('sectionEdit')
            ->label(__('layup::builder.section_settings'))
            ->slideOver()
            ->fillForm(fn (array $arguments): array => BaseView::normalizeSettingsForForm(collect($this->getState()['sections'] ?? [])->firstWhere('id', $arguments['id'] ?? null)['settings'] ?? []))
            ->schema(Section::getFormSchema())
            ->action(function (array $data, array $arguments): void {
                $state = $this->getState();
                foreach ($state['sections'] ?? [] as $index => $section) {
                    if ($section['id'] === ($arguments['id'] ?? null)) {
                        $state['sections'][$index]['settings'] = $data;
                    }
                }
                $this->state($state);
                $this->getLivewire()->dispatch('layup-sections-updated', content: $state, statePath: $this->getStatePath());
            });
    }

    public function sectionDeleteAction(): Action
    {
        return Action::make('sectionDelete')
            ->label(__('layup::builder.delete_section'))
            ->color('danger')
            ->requiresConfirmation()
            ->modalDescription(__('layup::builder.delete_section_confirm'))
            ->action(function (array $arguments): void {
                $state = $this->getState();
                $state['sections'] = array_values(array_filter($state['sections'] ?? [], fn (array $section): bool => $section['id'] !== ($arguments['id'] ?? null)));
                $this->state($state);
                $this->getLivewire()->dispatch('layup-sections-updated', content: $state, statePath: $this->getStatePath());
            });
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function sectionAdd(): array
    {
        $state = $this->getState() ?? [];
        if (! array_key_exists('sections', $state)) {
            $state['sections'] = empty($state['rows']) ? [] : [[
                'id' => 'section_' . Str::random(8), 'settings' => [], 'rows' => $state['rows'],
            ]];
            unset($state['rows']);
        }
        $state['sections'][] = ['id' => 'section_' . Str::random(8), 'settings' => [], 'rows' => []];
        $this->state($state);

        return $state;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function sectionDuplicate(string $id): array
    {
        $state = $this->getState();
        $index = array_search($id, array_column($state['sections'] ?? [], 'id'), true);
        if ($index !== false) {
            $clone = $state['sections'][$index];
            $clone['id'] = 'section_' . Str::random(8);
            $clone['rows'] = array_map(fn (array $row): array => $this->rowDeepClone($row), $clone['rows'] ?? []);
            array_splice($state['sections'], $index + 1, 0, [$clone]);
        }
        $this->state($state);

        return $state;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function sectionMove(string $id, string $direction): array
    {
        $state = $this->getState();
        $index = array_search($id, array_column($state['sections'] ?? [], 'id'), true);
        if ($index !== false) {
            $target = $direction === 'up' ? $index - 1 : $index + 1;
            if (isset($state['sections'][$target])) {
                [$state['sections'][$index], $state['sections'][$target]] = [$state['sections'][$target], $state['sections'][$index]];
            }
        }
        $this->state($state);

        return $state;
    }
}
