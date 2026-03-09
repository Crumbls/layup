<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

use Crumbls\Layup\View\BaseWidget;
use Crumbls\Layup\View\Column;
use Crumbls\Layup\View\Row;

class LayupContent
{
    protected array $content;

    public function __construct(mixed $content)
    {
        $this->content = $this->normalize($content);
    }

    public function toHtml(): string
    {
        return implode("\n", array_map(
            fn (Row $row) => $row->render()->render(),
            $this->getContentTree(),
        ));
    }

    /**
     * Get sections with their hydrated row trees.
     * Returns array of ['settings' => [...], 'rows' => [Row, ...]]
     */
    public function getSectionTree(): array
    {
        if (array_key_exists('sections', $this->content)) {
            $sections = $this->content['sections'];
        } else {
            $sections = [['settings' => [], 'rows' => $this->content['rows'] ?? []]];
        }

        return array_map(fn (array $sectionData): array => [
            'settings' => $sectionData['settings'] ?? [],
            'rows' => $this->buildRowTree($sectionData['rows'] ?? []),
        ], $sections);
    }

    /**
     * Get a flat list of hydrated Row objects across all sections.
     *
     * @return array<Row>
     */
    public function getContentTree(): array
    {
        if (array_key_exists('sections', $this->content)) {
            $rows = [];
            foreach ($this->content['sections'] as $section) {
                foreach ($section['rows'] ?? [] as $row) {
                    $rows[] = $row;
                }
            }
        } else {
            $rows = $this->content['rows'] ?? [];
        }

        return $this->buildRowTree($rows);
    }

    /**
     * Hydrate raw row data into Row → Column → Widget object trees.
     *
     * @return array<Row>
     */
    protected function buildRowTree(array $rows): array
    {
        $this->ensureWidgetsRegistered();

        $registry = app(WidgetRegistry::class);

        return array_map(function (array $rowData) use ($registry): Row {
            $columns = array_map(function (array $colData) use ($registry): Column {
                $widgets = array_values(array_filter(array_map(
                    function (array $widgetData) use ($registry) {
                        $type = $widgetData['type'] ?? null;
                        if (! is_string($type) || $type === '') {
                            return null;
                        }

                        $class = $registry->get($type);

                        return $class ? $class::make($widgetData['data'] ?? []) : null;
                    },
                    $colData['widgets'] ?? []
                )));

                return Column::make(
                    data: $colData['settings'] ?? [],
                    children: $widgets,
                )->span($colData['span'] ?? 12);
            }, $rowData['columns'] ?? []);

            return Row::make(
                data: $rowData['settings'] ?? [],
                children: $columns,
            );
        }, $rows);
    }

    protected function normalize(mixed $content): array
    {
        if (is_array($content)) {
            return $content;
        }

        if (is_string($content) && $content !== '') {
            return json_decode($content, true) ?? [];
        }

        return [];
    }

    protected function ensureWidgetsRegistered(): void
    {
        $registry = app(WidgetRegistry::class);

        if (count($registry->all()) > 0) {
            return;
        }

        foreach (config('layup.widgets', []) as $widgetClass) {
            $registry->register($widgetClass);
        }

        $this->discoverAppWidgets($registry);
    }

    protected function discoverAppWidgets(WidgetRegistry $registry): void
    {
        $namespace = config('layup.widget_discovery.namespace', 'App\\Layup\\Widgets');
        $directory = config('layup.widget_discovery.directory') ?? app_path('Layup/Widgets');

        if (! is_dir($directory)) {
            return;
        }

        foreach (new \DirectoryIterator($directory) as $file) {
            if ($file->isDot() || $file->getExtension() !== 'php') {
                continue;
            }

            $className = "{$namespace}\\{$file->getBasename('.php')}";

            if (
                class_exists($className)
                && is_subclass_of($className, BaseWidget::class)
                && ! $registry->has($className::getType())
            ) {
                $registry->register($className);
            }
        }
    }
}
