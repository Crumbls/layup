<?php

declare(strict_types=1);

namespace Crumbls\Layup\Forms\Components\Traits;

use Crumbls\Layup\Support\ContentWalker;
use Crumbls\Layup\View\BaseView;
use Crumbls\Layup\View\Column;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Components\Attributes\ExposedLivewireMethod;
use Illuminate\Support\Str;
use Livewire\Attributes\Renderless;

/**
 * Column operations for the LayupBuilder field.
 */
trait HandlesColumns
{
    // ─── Actions (Filament modal/slideover) ──────────────────

    #[ExposedLivewireMethod]
    public function columnDeleteAction(): Action
    {
        return Action::make('columnDelete')
            ->label('Delete Column')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Delete Column')
            ->modalDescription('Are you sure you want to delete this column? All widgets inside it will be removed.')
            ->action(function (array $arguments) {
                $rowId = $arguments['rowId'] ?? null;
                $columnId = $arguments['columnId'] ?? null;

                if (! $rowId || ! $columnId) {
                    return;
                }

                $state = $this->getState();
                $state = ContentWalker::mapRows($state, function (array $row) use ($rowId, $columnId): array {
                    if ($row['id'] !== $rowId) {
                        return $row;
                    }

                    $row['columns'] = collect($row['columns'])
                        ->reject(fn ($col) => $col['id'] === $columnId)
                        ->values()
                        ->all();

                    return $row;
                });

                $this->state($state);

                Notification::make()
                    ->title('Column deleted')
                    ->success()
                    ->duration(2000)
                    ->send();

                $this->getLivewire()->dispatch(
                    'layup-column-deleted',
                    rowId: $rowId,
                    columnId: $columnId,
                    statePath: $this->getStatePath(),
                );
            });
    }

    #[ExposedLivewireMethod]
    public function columnEditAction(): Action
    {
        return Action::make('columnEdit')
            ->label('Column Settings')
            ->slideOver()
            ->fillForm(function (array $arguments): array {
                $rowId = $arguments['rowId'] ?? null;
                $columnId = $arguments['columnId'] ?? null;

                if (! $rowId || ! $columnId) {
                    return [];
                }

                $state = $this->getState();
                $row = collect(ContentWalker::extractRows($state))->firstWhere('id', $rowId);
                $col = collect($row['columns'] ?? [])->firstWhere('id', $columnId);

                if (! $col) {
                    return [];
                }

                return array_merge(
                    BaseView::normalizeSettingsForForm($col['settings'] ?? []),
                    ['span' => $col['span'] ?? ['sm' => 12, 'md' => 6, 'lg' => 6, 'xl' => 6]],
                );
            })
            ->schema(Column::getFormSchema())
            ->action(function (array $data, array $arguments): void {
                $rowId = $arguments['rowId'] ?? null;
                $columnId = $arguments['columnId'] ?? null;

                if (! $rowId || ! $columnId) {
                    return;
                }

                $state = $this->getState();
                $state = ContentWalker::mapRows($state, function (array $row) use ($rowId, $columnId, $data): array {
                    if ($row['id'] !== $rowId) {
                        return $row;
                    }

                    $row['columns'] = collect($row['columns'])
                        ->map(function (array $col) use ($columnId, $data): array {
                            if ($col['id'] !== $columnId) {
                                return $col;
                            }

                            $col['span'] = $data['span'] ?? $col['span'];
                            unset($data['span']);
                            $col['settings'] = $data;

                            return $col;
                        })
                        ->all();

                    return $row;
                });

                $this->state($state);

                Notification::make()
                    ->title('Column updated')
                    ->success()
                    ->duration(2000)
                    ->send();

                // Find the updated column to send full data back
                $updatedCol = null;
                foreach (ContentWalker::extractRows($state) as $r) {
                    if ($r['id'] === $rowId) {
                        foreach ($r['columns'] as $c) {
                            if ($c['id'] === $columnId) {
                                $updatedCol = $c;
                                break 2;
                            }
                        }
                    }
                }

                $this->getLivewire()->dispatch(
                    'layup-column-updated',
                    rowId: $rowId,
                    columnId: $columnId,
                    span: $updatedCol['span'] ?? [],
                    settings: $updatedCol['settings'] ?? [],
                    statePath: $this->getStatePath(),
                );
            });
    }

    // ─── Exposed Livewire Methods (no modal) ────────────────

    #[Renderless]
    #[ExposedLivewireMethod]
    public function columnAdd(string $rowId, int $span = 6): array
    {
        $state = $this->getState();

        if (! is_array($state) || (! isset($state['rows']) && ! isset($state['sections']))) {
            return [];
        }

        $column = null;

        $state = ContentWalker::mapRows($state, function (array $row) use ($rowId, $span, &$column): array {
            if ($row['id'] !== $rowId) {
                return $row;
            }

            $column = [
                'id' => 'col_' . Str::random(8),
                'span' => ['sm' => 12, 'md' => $span, 'lg' => $span, 'xl' => $span],
                'settings' => ['padding' => ['unit' => 'rem', 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1]],
                'widgets' => [],
            ];

            $row['columns'][] = $column;

            return $row;
        });

        if (! $column) {
            return [];
        }

        $this->state($state);

        Notification::make()
            ->title('Column added')
            ->success()
            ->duration(2000)
            ->send();

        return $column;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function columnMove(string $rowId, string $columnId, string $direction): bool
    {
        $state = $this->getState();

        if (! is_array($state) || (! isset($state['rows']) && ! isset($state['sections']))) {
            return false;
        }

        $state = ContentWalker::mapRows($state, function (array $row) use ($rowId, $columnId, $direction): array {
            if ($row['id'] !== $rowId) {
                return $row;
            }

            $cols = collect($row['columns']);
            $index = $cols->search(fn ($c) => $c['id'] === $columnId);

            if ($index === false) {
                return $row;
            }

            $newIndex = $direction === 'left' ? $index - 1 : $index + 1;

            if ($newIndex < 0 || $newIndex >= $cols->count()) {
                return $row;
            }

            $arr = $cols->all();
            [$arr[$index], $arr[$newIndex]] = [$arr[$newIndex], $arr[$index]];
            $row['columns'] = array_values($arr);

            return $row;
        });

        $this->state($state);

        return true;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function columnResize(string $rowId, array $spans): bool
    {
        $state = $this->getState();

        if (! is_array($state) || (! isset($state['rows']) && ! isset($state['sections']))) {
            return false;
        }

        $state = ContentWalker::mapRows($state, function (array $row) use ($rowId, $spans): array {
            if ($row['id'] !== $rowId) {
                return $row;
            }

            foreach ($row['columns'] as $i => &$col) {
                if (isset($spans[$i])) {
                    $col['span'] = $spans[$i];
                }
            }

            return $row;
        });

        $this->state($state);

        return true;
    }
}
