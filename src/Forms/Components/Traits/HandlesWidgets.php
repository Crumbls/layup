<?php

declare(strict_types=1);

namespace Crumbls\Layup\Forms\Components\Traits;

use Crumbls\Layup\Support\ContentValidator;
use Crumbls\Layup\Support\WidgetRegistry;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Components\Attributes\ExposedLivewireMethod;
use Illuminate\Support\Str;
use Livewire\Attributes\Renderless;

/**
 * Widget operations for the LayupBuilder field.
 */
trait HandlesWidgets
{
    // ─── Actions (Filament modal/slideover) ──────────────────

    #[ExposedLivewireMethod]
    public function widgetEditAction(): Action
    {
        $registry = app(WidgetRegistry::class);

        return Action::make('widgetEdit')
            ->label('Edit Widget')
            ->slideOver()
            ->fillForm(function (array $arguments): array {
                $widget = $this->findWidget(
                    $arguments['rowId'] ?? null,
                    $arguments['columnId'] ?? null,
                    $arguments['widgetId'] ?? null,
                );

                return $widget['data'] ?? [];
            })
            ->form(function (array $arguments) use ($registry): array {
                $widget = $this->findWidget(
                    $arguments['rowId'] ?? null,
                    $arguments['columnId'] ?? null,
                    $arguments['widgetId'] ?? null,
                );

                $type = $widget['type'] ?? 'text';

                return $registry->getFormSchema($type);
            })
            ->action(function (array $data, array $arguments) use ($registry): void {
                $rowId = $arguments['rowId'] ?? null;
                $columnId = $arguments['columnId'] ?? null;
                $widgetId = $arguments['widgetId'] ?? null;

                if (! $rowId || ! $columnId || ! $widgetId) {
                    return;
                }

                $widget = $this->findWidget($rowId, $columnId, $widgetId);
                $widgetType = $widget['type'] ?? 'text';
                $data = $registry->fireOnSave($widgetType, $data);

                $state = $this->getState();
                $state['rows'] = collect($state['rows'] ?? [])
                    ->map(function (array $row) use ($rowId, $columnId, $widgetId, $data): array {
                        if ($row['id'] !== $rowId) {
                            return $row;
                        }

                        $row['columns'] = collect($row['columns'])
                            ->map(function (array $col) use ($columnId, $widgetId, $data): array {
                                if ($col['id'] !== $columnId) {
                                    return $col;
                                }

                                $col['widgets'] = collect($col['widgets'])
                                    ->map(function (array $widget) use ($widgetId, $data): array {
                                        if ($widget['id'] === $widgetId) {
                                            $widget['data'] = $data;
                                        }

                                        return $widget;
                                    })
                                    ->all();

                                return $col;
                            })
                            ->all();

                        return $row;
                    })
                    ->all();

                $this->state($state);

                // Validate and notify
                $result = (new ContentValidator)->validate($state);
                if (! $result->passes()) {
                    Notification::make()
                        ->title('Widget saved with warnings')
                        ->body(implode("\n", array_slice($result->errors(), 0, 3)))
                        ->warning()
                        ->duration(5000)
                        ->send();
                } else {
                    Notification::make()
                        ->title('Widget updated')
                        ->success()
                        ->duration(2000)
                        ->send();
                }

                $this->getLivewire()->dispatch(
                    'layup-widget-updated',
                    rowId: $rowId,
                    columnId: $columnId,
                    widgetId: $widgetId,
                    data: $data,
                    statePath: $this->getStatePath(),
                );
            });
    }

    #[ExposedLivewireMethod]
    public function widgetDeleteAction(): Action
    {
        return Action::make('widgetDelete')
            ->label('Delete Widget')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Delete Widget')
            ->modalDescription('Are you sure you want to delete this widget?')
            ->action(function (array $arguments) {
                $rowId = $arguments['rowId'] ?? null;
                $columnId = $arguments['columnId'] ?? null;
                $widgetId = $arguments['widgetId'] ?? null;

                if (! $rowId || ! $columnId || ! $widgetId) {
                    return;
                }

                // Fire onDelete hook
                $widget = $this->findWidget($rowId, $columnId, $widgetId);
                if ($widget) {
                    $registry = app(WidgetRegistry::class);
                    $registry->fireOnDelete($widget['type'], $widget['data'] ?? []);
                }

                $state = $this->getState();
                $state['rows'] = collect($state['rows'] ?? [])
                    ->map(function (array $row) use ($rowId, $columnId, $widgetId): array {
                        if ($row['id'] !== $rowId) {
                            return $row;
                        }

                        $row['columns'] = collect($row['columns'])
                            ->map(function (array $col) use ($columnId, $widgetId): array {
                                if ($col['id'] !== $columnId) {
                                    return $col;
                                }

                                $col['widgets'] = collect($col['widgets'])
                                    ->reject(fn ($w) => $w['id'] === $widgetId)
                                    ->values()
                                    ->all();

                                return $col;
                            })
                            ->all();

                        return $row;
                    })
                    ->all();

                $this->state($state);

                Notification::make()
                    ->title('Widget deleted')
                    ->success()
                    ->duration(2000)
                    ->send();

                $this->getLivewire()->dispatch(
                    'layup-widget-deleted',
                    rowId: $rowId,
                    columnId: $columnId,
                    widgetId: $widgetId,
                    statePath: $this->getStatePath(),
                );
            });
    }

    // ─── Exposed Livewire Methods (no modal) ────────────────

    #[Renderless]
    #[ExposedLivewireMethod]
    public function widgetAdd(string $rowId, string $columnId, string $widgetType, int $position = -1): array
    {
        $registry = app(WidgetRegistry::class);
        $defaults = $registry->getDefaultData($widgetType);
        $data = $registry->fireOnCreate($widgetType, $defaults);

        $widget = [
            'id' => 'widget_' . Str::random(8),
            'type' => $widgetType,
            'data' => $data,
        ];

        $state = $this->getState();

        $state['rows'] = collect($state['rows'] ?? [])
            ->map(function (array $row) use ($rowId, $columnId, $widget, $position): array {
                if ($row['id'] !== $rowId) {
                    return $row;
                }

                $row['columns'] = collect($row['columns'])
                    ->map(function (array $col) use ($columnId, $widget, $position): array {
                        if ($col['id'] !== $columnId) {
                            return $col;
                        }

                        if ($position < 0) {
                            $col['widgets'][] = $widget;
                        } else {
                            array_splice($col['widgets'], $position, 0, [$widget]);
                        }

                        return $col;
                    })
                    ->all();

                return $row;
            })
            ->all();

        $this->state($state);

        return $widget;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function widgetDuplicate(string $rowId, string $columnId, string $widgetId): array
    {
        $state = $this->getState();
        $clone = null;

        $state['rows'] = collect($state['rows'] ?? [])
            ->map(function (array $row) use ($rowId, $columnId, $widgetId, &$clone): array {
                if ($row['id'] !== $rowId) {
                    return $row;
                }

                $row['columns'] = collect($row['columns'])
                    ->map(function (array $col) use ($columnId, $widgetId, &$clone): array {
                        if ($col['id'] !== $columnId) {
                            return $col;
                        }

                        $index = collect($col['widgets'])->search(fn ($w) => $w['id'] === $widgetId);
                        if ($index === false) {
                            return $col;
                        }

                        $original = $col['widgets'][$index];
                        $clone = [
                            'id' => 'widget_' . Str::random(8),
                            'type' => $original['type'],
                            'data' => $original['data'] ?? [],
                        ];

                        array_splice($col['widgets'], $index + 1, 0, [$clone]);

                        return $col;
                    })
                    ->all();

                return $row;
            })
            ->all();

        if (! $clone) {
            return [];
        }

        $this->state($state);

        Notification::make()
            ->title('Widget duplicated')
            ->success()
            ->duration(2000)
            ->send();

        return $clone;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function widgetMove(string $rowId, string $columnId, string $widgetId, string $direction): bool
    {
        $state = $this->getState();

        $state['rows'] = collect($state['rows'] ?? [])
            ->map(function (array $row) use ($rowId, $columnId, $widgetId, $direction): array {
                if ($row['id'] !== $rowId) {
                    return $row;
                }

                $row['columns'] = collect($row['columns'])
                    ->map(function (array $col) use ($columnId, $widgetId, $direction): array {
                        if ($col['id'] !== $columnId) {
                            return $col;
                        }

                        $widgets = collect($col['widgets']);
                        $index = $widgets->search(fn ($w) => $w['id'] === $widgetId);
                        if ($index === false) {
                            return $col;
                        }

                        $newIndex = $direction === 'up' ? $index - 1 : $index + 1;
                        if ($newIndex < 0 || $newIndex >= $widgets->count()) {
                            return $col;
                        }

                        $arr = $widgets->all();
                        [$arr[$index], $arr[$newIndex]] = [$arr[$newIndex], $arr[$index]];
                        $col['widgets'] = array_values($arr);

                        return $col;
                    })
                    ->all();

                return $row;
            })
            ->all();

        $this->state($state);

        return true;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function widgetMoveTo(
        string $sourceRowId,
        string $sourceColId,
        string $widgetId,
        string $targetRowId,
        string $targetColId,
        int $position
    ): bool {
        $state = $this->getState();
        $widget = null;

        // Remove from source
        $state['rows'] = collect($state['rows'] ?? [])
            ->map(function (array $row) use ($sourceRowId, $sourceColId, $widgetId, &$widget): array {
                if ($row['id'] !== $sourceRowId) {
                    return $row;
                }

                $row['columns'] = collect($row['columns'])
                    ->map(function (array $col) use ($sourceColId, $widgetId, &$widget): array {
                        if ($col['id'] !== $sourceColId) {
                            return $col;
                        }

                        $index = collect($col['widgets'])->search(fn ($w) => $w['id'] === $widgetId);
                        if ($index === false) {
                            return $col;
                        }

                        $widget = $col['widgets'][$index];
                        array_splice($col['widgets'], $index, 1);

                        return $col;
                    })
                    ->all();

                return $row;
            })
            ->all();

        if (! $widget) {
            return false;
        }

        // Insert into target
        $state['rows'] = collect($state['rows'])
            ->map(function (array $row) use ($targetRowId, $targetColId, $widget, $position): array {
                if ($row['id'] !== $targetRowId) {
                    return $row;
                }

                $row['columns'] = collect($row['columns'])
                    ->map(function (array $col) use ($targetColId, $widget, $position): array {
                        if ($col['id'] !== $targetColId) {
                            return $col;
                        }

                        array_splice($col['widgets'], $position, 0, [$widget]);

                        return $col;
                    })
                    ->all();

                return $row;
            })
            ->all();

        $this->state($state);

        return true;
    }

    #[Renderless]
    #[ExposedLivewireMethod]
    public function widgetUpdateContent(string $rowId, string $columnId, string $widgetId, string $content): bool
    {
        $state = $this->getState();

        $state['rows'] = collect($state['rows'] ?? [])
            ->map(function (array $row) use ($rowId, $columnId, $widgetId, $content): array {
                if ($row['id'] !== $rowId) {
                    return $row;
                }

                $row['columns'] = collect($row['columns'])
                    ->map(function (array $col) use ($columnId, $widgetId, $content): array {
                        if ($col['id'] !== $columnId) {
                            return $col;
                        }

                        $col['widgets'] = collect($col['widgets'])
                            ->map(function (array $widget) use ($widgetId, $content): array {
                                if ($widget['id'] === $widgetId) {
                                    $widget['data']['content'] = $content;
                                }

                                return $widget;
                            })
                            ->all();

                        return $col;
                    })
                    ->all();

                return $row;
            })
            ->all();

        $this->state($state);

        return true;
    }

    // ─── Helpers ─────────────────────────────────────────────

    protected function findWidget(?string $rowId, ?string $columnId, ?string $widgetId): ?array
    {
        if (! $rowId || ! $columnId || ! $widgetId) {
            return null;
        }

        $state = $this->getState();
        $row = collect($state['rows'] ?? [])->firstWhere('id', $rowId);

        if (! $row) {
            return null;
        }

        $col = collect($row['columns'] ?? [])->firstWhere('id', $columnId);

        if (! $col) {
            return null;
        }

        return collect($col['widgets'] ?? [])->firstWhere('id', $widgetId);
    }
}
