<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

use Crumbls\Layup\Support\Concerns\RegistersWidgets;
use Crumbls\Layup\View\Column;
use Crumbls\Layup\View\Row;
use Illuminate\Contracts\Support\Htmlable;

class LayupContent implements Htmlable
{
    use RegistersWidgets;

    protected array $content;

    public function __construct(mixed $content)
    {
        $this->content = $this->normalize($content);
    }

    public function toHtml(): string
    {
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
            $this->getContentTree(),
        )));
    }

    /**
     * @return array<int, array{settings: array, rows: array<Row>}>
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
                            return;
                        }

                        $class = $registry->get($type);
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
     * @return array<int, array{type: string, children: array, data?: array, span?: array}>
     */
    public function toArray(): array
    {
        return $this->serializeNodes($this->getContentTree());
    }

    /**
     * @param  array<\Crumbls\Layup\View\BaseView>  $nodes
     */
    protected function serializeNodes(array $nodes): array
    {
        return array_values(array_map(function (\Crumbls\Layup\View\BaseView $node): array {
            $entry = ['type' => $this->resolveNodeType($node)];

            if ($node instanceof Column) {
                $entry['span'] = $node->getSpan();
            }

            if ($node instanceof \Crumbls\Layup\View\BaseWidget) {
                $entry['data'] = $node->getData();
            }

            $children = $node->getChildren();

            if ($children !== []) {
                $entry['children'] = $this->serializeNodes($children);
            }

            return $entry;
        }, $nodes));
    }

    protected function resolveNodeType(\Crumbls\Layup\View\BaseView $node): string
    {
        if ($node instanceof Row) {
            return 'row';
        }

        if ($node instanceof Column) {
            return 'column';
        }

        if ($node instanceof \Crumbls\Layup\View\BaseWidget) {
            return $node::getType();
        }

        return 'unknown';
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
}
