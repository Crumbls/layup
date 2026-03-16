<?php

declare(strict_types=1);

namespace Crumbls\Layup\Concerns;

use Crumbls\Layup\Support\SafelistCollector;
use Crumbls\Layup\Support\WidgetRegistry;
use Crumbls\Layup\View\Column;
use Crumbls\Layup\View\Row;

trait HasLayupContent
{
    /**
     * Get the column name that holds Layup content.
     * Override $layupContentColumn on your model to change.
     */
    protected function getLayupContentColumn(): string
    {
        return $this->layupContentColumn ?? 'content';
    }

    /**
     * Get the raw Layup content array.
     */
    protected function getLayupContent(): array
    {
        $column = $this->getLayupContentColumn();

        return $this->{$column} ?? [];
    }

    /**
     * Get sections with their row trees.
     *
     * @return array<int, array{settings: array, rows: array<Row>}>
     */
    public function getSectionTree(): array
    {
        $content = $this->getLayupContent();

        if (array_key_exists('sections', $content)) {
            $sections = $content['sections'];
        } else {
            $sections = [['settings' => [], 'rows' => $content['rows'] ?? []]];
        }

        return array_map(fn (array $sectionData): array => [
            'settings' => $sectionData['settings'] ?? [],
            'rows' => $this->buildRowTree($sectionData['rows'] ?? []),
        ], $sections);
    }

    /**
     * Get flat list of Row objects (all sections merged).
     *
     * @return array<Row>
     */
    public function getContentTree(): array
    {
        $content = $this->getLayupContent();
        $rows = $content['rows'] ?? [];

        if (array_key_exists('sections', $content)) {
            $rows = [];

            foreach ($content['sections'] as $section) {
                foreach ($section['rows'] ?? [] as $row) {
                    $rows[] = $row;
                }
            }
        }

        return $this->buildRowTree($rows);
    }

    /**
     * Build a tree of Row/Column/Widget objects from raw row data.
     *
     * @return array<Row>
     */
    protected function buildRowTree(array $rows): array
    {
        $registry = app(WidgetRegistry::class);

        return array_map(function (array $rowData) use ($registry): Row {
            $columns = array_map(function (array $colData) use ($registry): Column {
                $widgets = array_values(array_filter(array_map(
                    function (array $widgetData) use ($registry) {
                        $type = $widgetData['type'] ?? null;
                        $class = $type ? $registry->get($type) : null;

                        if (! $class) {
                            return;
                        }

                        try {
                            $rawData = $widgetData['data'] ?? [];

                            return $class::make($class::prepareForRender($rawData));
                        } catch (\Throwable $e) {
                            logger()->error("Layup: Widget '{$type}' failed to instantiate", [
                                'error' => $e->getMessage(),
                            ]);

                            return;
                        }
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

    /**
     * Render the full content to an HTML string.
     */
    public function toHtml(): string
    {
        $tree = $this->getContentTree();

        return implode("\n", array_filter(array_map(
            function (Row $row): string {
                try {
                    return $row->render()->render();
                } catch (\Throwable $e) {
                    logger()->error('Layup: Row render failed', ['error' => $e->getMessage()]);

                    return app()->hasDebugModeEnabled()
                        ? '<!-- [Layup] Render error: ' . e($e->getMessage()) . ' -->'
                        : '';
                }
            },
            $tree,
        )));
    }

    /**
     * Get all Tailwind CSS classes used in content.
     *
     * @return array<string>
     */
    public function getUsedClasses(): array
    {
        return SafelistCollector::classesFromContent($this->getLayupContent());
    }

    /**
     * Get all inline CSS declarations used in content.
     *
     * @return array<string>
     */
    public function getUsedInlineStyles(): array
    {
        return SafelistCollector::inlineStylesFromContent($this->getLayupContent());
    }
}
