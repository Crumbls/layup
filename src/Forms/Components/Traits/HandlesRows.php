<?php

declare(strict_types=1);

namespace Crumbls\Layup\Forms\Components\Traits;

use Crumbls\Layup\Support\ContentWalker;
use Crumbls\Layup\Support\WidgetRegistry;
use Crumbls\Layup\View\BaseView;
use Crumbls\Layup\View\Row;
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
                $state = ContentWalker::mapRowGroups($state, fn (array $rows): array => array_values(array_filter($rows, fn (array $row): bool => $row['id'] !== $id))
                );

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
                $row = collect(ContentWalker::extractRows($state))->firstWhere('id', $id);

                $settings = array_replace(['layout' => 'flex', 'gap' => 'gap-4'], $row['settings'] ?? []);
                $settings['layout'] = ($settings['layout'] ?? null) === 'grid' ? 'grid' : 'flex';
                $settings['gap'] = in_array($settings['gap'], Row::GAPS, true) ? $settings['gap'] : 'gap-4';

                return BaseView::normalizeSettingsForForm($settings);
            })
            ->schema(Row::getFormSchema())
            ->action(function (array $data, array $arguments): void {
                $id = $arguments['id'] ?? null;

                if (! $id) {
                    return;
                }

                $state = $this->getState();
                $state = ContentWalker::mapRows($state, function (array $row) use ($id, $data): array {
                    if ($row['id'] === $id) {
                        $row['settings'] = array_replace($row['settings'] ?? [], $data);
                    }

                    return $row;
                });

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
    public function rowAdd(mixed $columns = [12], ?int $position = 0, ?string $sectionId = null): array
    {
        if (is_string($columns)) {
            $columns = json_decode($columns, true) ?? [$columns];
        }
        $columns = is_array($columns) ? $columns : [$columns];
        if ($columns === [] || count(array_filter($columns, fn ($span): bool => is_numeric($span) && (int) $span == $span && $span > 0 && $span <= 12)) !== count($columns) || array_sum($columns) != 12) {
            return [];
        }

        $row = [];
        $state = ContentWalker::mapRowGroups($this->getState() ?? [], function (array $rows, ?string $id) use ($sectionId, $columns, $position, &$row): array {
            if ($id !== $sectionId) {
                return $rows;
            }
            $order = min(max(0, $position ?? 0), count($rows));
            $row = [
                'id' => 'row_' . Str::random(8), 'order' => $order,
                'settings' => ['layout' => 'flex', 'direction' => 'row', 'justify' => 'start', 'align' => 'stretch', 'wrap' => 'wrap', 'full_width' => false],
                'columns' => array_map(fn ($span): array => [
                    'id' => 'col_' . Str::random(8),
                    'span' => ['sm' => 12, 'md' => (int) $span, 'lg' => (int) $span, 'xl' => (int) $span],
                    'settings' => ['padding' => ['unit' => 'rem', 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1]],
                    'widgets' => [],
                ], array_values($columns)),
            ];
            array_splice($rows, $order, 0, [$row]);

            return $rows;
        });
        $this->state($state);

        return $row;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function rowDuplicate(string $id): array
    {
        $clone = [];
        $state = ContentWalker::mapRowGroups($this->getState() ?? [], function (array $rows) use ($id, &$clone): array {
            $index = array_search($id, array_column($rows, 'id'), true);
            if ($index !== false) {
                $clone = $this->rowDeepClone($rows[$index]);
                $clone['order'] = $index + 1;
                array_splice($rows, $index + 1, 0, [$clone]);
            }

            return $rows;
        });
        $this->state($state);

        return $clone;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function rowMove(string $id, string $direction): array
    {
        $moved = [];
        $state = ContentWalker::mapRowGroups($this->getState() ?? [], function (array $rows) use ($id, $direction, &$moved): array {
            $index = array_search($id, array_column($rows, 'id'), true);
            if ($index === false) {
                return $rows;
            }
            $target = $direction === 'up' ? $index - 1 : $index + 1;
            if (isset($rows[$target])) {
                [$rows[$index], $rows[$target]] = [$rows[$target], $rows[$index]];
            }
            $moved = $rows;

            return $rows;
        });
        $this->state($state);

        return $moved;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function rowMoveTo(string $id, int $targetIndex): bool
    {
        $found = false;
        $state = ContentWalker::mapRowGroups($this->getState() ?? [], function (array $rows) use ($id, $targetIndex, &$found): array {
            $index = array_search($id, array_column($rows, 'id'), true);
            if ($index === false) {
                return $rows;
            }
            $found = true;
            $row = $rows[$index];
            array_splice($rows, $index, 1);
            array_splice($rows, max(0, min($targetIndex, count($rows))), 0, [$row]);

            return $rows;
        });
        $this->state($state);

        return $found;
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
