<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

use Closure;

class ContentWalker
{
    /**
     * Walk all widgets in a content structure, calling $callback for each.
     *
     * Handles both `sections` and legacy `rows` structures.
     *
     * @param  array  $content  The page content array
     * @param  Closure(string $type, array $data, array $path): void  $callback
     */
    public static function walkWidgets(array $content, Closure $callback): void
    {
        $rows = self::extractRows($content);

        foreach ($rows as $rowIndex => $row) {
            foreach ($row['columns'] ?? [] as $colIndex => $col) {
                foreach ($col['widgets'] ?? [] as $widgetIndex => $widget) {
                    $type = $widget['type'] ?? 'unknown';
                    $data = $widget['data'] ?? [];
                    $path = [
                        'row' => $rowIndex,
                        'column' => $colIndex,
                        'widget' => $widgetIndex,
                    ];

                    $callback($type, $data, $path);
                }
            }
        }
    }

    /**
     * Collect widget type counts from a content structure.
     *
     * @param  array  $content  The page content array
     * @return array<string, int> Widget type => count
     */
    public static function collectWidgetTypes(array $content): array
    {
        $types = [];

        self::walkWidgets($content, function (string $type) use (&$types): void {
            $types[$type] = ($types[$type] ?? 0) + 1;
        });

        return $types;
    }

    /**
     * Extract all rows from content, handling both sections and legacy rows.
     *
     * @return array<array>
     */
    public static function extractRows(array $content): array
    {
        if (! empty($content['sections'])) {
            $rows = [];

            foreach ($content['sections'] as $section) {
                foreach ($section['rows'] ?? [] as $row) {
                    $rows[] = $row;
                }
            }

            return $rows;
        }

        return $content['rows'] ?? [];
    }
}
