<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

use Illuminate\Database\Eloquent\Model;

/**
 * Resolves the container class string for a page using the precedence:
 *
 *   1. Page meta override:  $page->meta['layout']['container']  (preset key)
 *   2. Global preset:       config('layup.page_layout.default')
 *   3. Legacy fallback:     config('layup.frontend.max_width', 'container')
 *
 * Preset keys are mapped through `layup.page_layout.containers` to produce
 * the final Tailwind class string applied in row.blade.php.
 */
class PageLayout
{
    /**
     * Get the container class string applied to non-full-width rows for
     * the given page, or the global default when no page is supplied.
     */
    public static function resolve(?Model $page = null): string
    {
        $presets = static::presets();

        $key = $page?->meta['layout']['container'] ?? null;

        if (! $key || ! isset($presets[$key])) {
            $key = config('layup.page_layout.default');
        }

        if ($key && isset($presets[$key]['classes'])) {
            return (string) $presets[$key]['classes'];
        }

        return (string) config('layup.frontend.max_width', 'container');
    }

    /**
     * Build a flat key => label map for use in a Filament Select.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (static::presets() as $key => $preset) {
            $options[$key] = (string) ($preset['label'] ?? $key);
        }

        return $options;
    }

    /**
     * Return all preset class strings — used by SafelistCollector so
     * Tailwind picks them up regardless of which preset a page selects.
     *
     * @return array<int, string>
     */
    public static function allPresetClasses(): array
    {
        $classes = [];

        foreach (static::presets() as $preset) {
            if (! empty($preset['classes'])) {
                foreach (preg_split('/\s+/', trim((string) $preset['classes'])) as $class) {
                    if ($class !== '') {
                        $classes[] = $class;
                    }
                }
            }
        }

        return array_values(array_unique($classes));
    }

    /**
     * Build a flat key => label map of view templates available for
     * per-page selection. Empty array means no per-page override is
     * exposed in the UI (host app hasn't registered any templates).
     *
     * @return array<string, string>
     */
    public static function templateOptions(): array
    {
        $options = [];

        foreach ((array) config('layup.page_layout.templates', []) as $key => $label) {
            $options[$key] = (string) $label;
        }

        return $options;
    }

    /**
     * @return array<string, array{label?: string, classes?: string}>
     */
    protected static function presets(): array
    {
        return (array) config('layup.page_layout.containers', []);
    }
}
