<?php

declare(strict_types=1);

namespace Crumbls\Layup\Forms\Components\Traits;

use Crumbls\Layup\View\Row;
use Crumbls\Layup\Support\WidgetRegistry;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Components\Attributes\ExposedLivewireMethod;
use Illuminate\Support\Str;
use Livewire\Attributes\Renderless;

/**
 * Row operations for the LayupBuilder field.
 * Each public concern gets its own trait to keep things sane.
 */
trait HandlesRows
{
    // ─── Actions (Filament modal/slideover) ──────────────────

    #[ExposedLivewireMethod]
    public function rowDeleteAction(): Action
    {
        return Action::make('rowDelete')
            ->label('Delete Row')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Delete Row')
            ->modalDescription('Are you sure you want to delete this row? All columns and widgets inside it will be removed.')
            ->action(function (array $arguments) {
                if (! array_key_exists('id', $arguments)) {
                    return;
                }

                $id = $arguments['id'];

                $state = $this->getState();
                $state['rows'] = collect($state['rows'] ?? [])
                    ->reject(fn ($row) => $row['id'] === $id)
                    ->values()
                    ->all();

                $this->state($state);

                Notification::make()
                    ->title('Row deleted')
                    ->success()
                    ->duration(2000)
                    ->send();

                $this->syncContent();

                $this->getLivewire()->dispatch(
                    'layup-row-deleted',
                    id: $id,
                    statePath: $this->getStatePath(),
                );
            });
    }

    #[ExposedLivewireMethod]
    public function rowEditAction(): Action
    {
        return Action::make('rowEdit')
            ->label('Row Settings')
            ->slideOver()
            ->fillForm(function (array $arguments): array {
                $id = $arguments['id'] ?? null;

                if (! $id) {
                    return [];
                }

                $state = $this->getState();
                $row = collect($state['rows'] ?? [])->firstWhere('id', $id);

                return $row['settings'] ?? [];
            })
            ->schema(Row::getFormSchema())
            ->action(function (array $data, array $arguments): void {
                $id = $arguments['id'] ?? null;

                if (! $id) {
                    return;
                }

                $state = $this->getState();
                $state['rows'] = collect($state['rows'] ?? [])
                    ->map(function (array $row) use ($id, $data): array {
                        if ($row['id'] === $id) {
                            $row['settings'] = $data;
                        }

                        return $row;
                    })
                    ->all();

                $this->state($state);

                Notification::make()
                    ->title('Row updated')
                    ->success()
                    ->duration(2000)
                    ->send();

                $this->getLivewire()->dispatch(
                    'layup-row-updated',
                    id: $id,
                    settings: $data,
                    statePath: $this->getStatePath(),
                );
            });
    }

    // ─── Exposed Livewire Methods (no modal) ────────────────

    #[Renderless]
    #[ExposedLivewireMethod]
    public function rowAdd(mixed $columns = [12], ?int $position = 0): array
    {
        if (is_string($columns)) {
            $decoded = json_decode($columns, true);
            $columns = is_array($decoded) ? $decoded : [$columns];
        }

        if (is_int($columns)) {
            $columns = [$columns];
        }

        if (! is_array($columns) || empty($columns)) {
            $columns = [12];
        }

        $position = max(0, $position);

        $columns = array_filter($columns, 'is_numeric');

        if (array_sum($columns) != 12) {
            return [];
        }

        $state = $this->getState();

        if (! is_array($state)) {
            $state = [];
        }

        if (! array_key_exists('rows', $state) || ! is_array($state['rows'])) {
            $state['rows'] = [];
        }

        $existingIds = array_column($state['rows'], 'id');

        do {
            $id = 'row_' . Str::random(8);
        } while (in_array($id, $existingIds));

        $position = min($position, count($state['rows']));

        $row = [
            'id' => $id,
            'order' => $position,
            'settings' => [
                'direction' => 'row',
                'justify' => 'start',
                'align' => 'stretch',
                'wrap' => 'wrap',
                'full_width' => false,
            ],
            'columns' => collect($columns)->map(fn (int $span): array => [
                'id' => 'col_' . Str::random(8),
                'span' => ['sm' => 12, 'md' => $span, 'lg' => $span, 'xl' => $span],
                'settings' => ['padding' => 'p-4', 'background' => 'transparent'],
                'widgets' => [],
            ])->values()->all(),
        ];

        array_splice($state['rows'], $position, 0, [$row]);

        $this->state($state);

        return $row;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function rowDuplicate(string $id): array
    {
        $state = $this->getState();

        if (! is_array($state) || ! isset($state['rows']) || ! is_array($state['rows'])) {
            return [];
        }

        $existingIds = array_column($state['rows'], 'id');
        $position = array_search($id, $existingIds);

        if ($position === false) {
            return [];
        }

        $original = $state['rows'][$position];
        $position++;

        $clone = $this->rowDeepClone($original);
        $clone['order'] = $position;

        array_splice($state['rows'], $position, 0, [$clone]);

        $this->state($state);

        Notification::make()
            ->title(__('layup::notifications.row_duplicated'))
            ->success()
            ->duration(2000)
            ->send();

        return $clone;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function rowMove(string $id, string $direction): array
    {
        $state = $this->getState();

        if (! is_array($state) || ! isset($state['rows'])) {
            return [];
        }

        $rows = collect($state['rows']);
        $index = $rows->search(fn ($r) => $r['id'] === $id);

        if ($index === false) {
            return [];
        }

        $newIndex = $direction === 'up' ? $index - 1 : $index + 1;

        if ($newIndex < 0 || $newIndex >= $rows->count()) {
            return [];
        }

        $arr = $rows->all();
        [$arr[$index], $arr[$newIndex]] = [$arr[$newIndex], $arr[$index]];
        $state['rows'] = array_values($arr);

        $this->state($state);

        return $state['rows'];
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function rowMoveTo(string $id, int $targetIndex): bool
    {
        $state = $this->getState();

        if (! is_array($state) || ! isset($state['rows'])) {
            return false;
        }

        $rows = $state['rows'];
        $sourceIndex = collect($rows)->search(fn ($r) => $r['id'] === $id);

        if ($sourceIndex === false) {
            return false;
        }

        $row = $rows[$sourceIndex];
        array_splice($rows, $sourceIndex, 1);
        array_splice($rows, $targetIndex, 0, [$row]);

        $state['rows'] = $rows;
        $this->state($state);

        return true;
    }

    // ─── Helpers ─────────────────────────────────────────────

    protected function rowDeepClone(array $row): array
    {
        $registry = app(WidgetRegistry::class);
        $clone = $row;
        $clone['id'] = 'row_' . Str::random(8);
        $clone['columns'] = collect($row['columns'])->map(function (array $col) use ($registry): array {
            $col['id'] = 'col_' . Str::random(8);
            $col['widgets'] = collect($col['widgets'] ?? [])->map(function (array $widget) use ($registry): array {
                $widget['id'] = 'widget_' . Str::random(8);
                $widget['data'] = $registry->fireOnDuplicate($widget['type'] ?? '', $widget['data'] ?? []);

                return $widget;
            })->all();

            return $col;
        })->all();

        return $clone;
    }
}
