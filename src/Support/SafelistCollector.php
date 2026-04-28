<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

use Crumbls\Layup\Events\SafelistChanged;
use Crumbls\Layup\Models\Page;

/**
 * Collects all Tailwind CSS classes and inline styles used across
 * Layup page content so they can be added to a Tailwind safelist.
 *
 * Usage:
 *   // All published pages:
 *   $classes = SafelistCollector::classes();
 *
 *   // Specific pages:
 *   $classes = SafelistCollector::classesForPages(Page::where('slug', 'home')->get());
 *
 *   // Raw from content array:
 *   $classes = SafelistCollector::classesFromContent($page->content);
 *
 * In your Tailwind config (v4 CSS or v3 config):
 *
 *   @source "../../storage/layup-safelist.txt";
 *
 * Or generate the file with the Artisan command:
 *   php artisan layup:safelist
 */
class SafelistCollector
{
    /**
     * Collect all unique CSS classes from all published pages,
     * merged with the static safelist of all possible plugin classes.
     *
     * @return array<string>
     */
    public static function classes(): array
    {
        $modelClass = config('layup.pages.model', Page::class);

        return array_values(array_unique(array_merge(
            static::staticClasses(),
            PageLayout::allPresetClasses(),
            config('layup.safelist.extra_classes', []),
            static::classesForPages($modelClass::published()->get()),
        )));
    }

    /**
     * Get the static set of all Tailwind utility classes the plugin can generate.
     * These are predictable/finite (column widths, flex utilities, gap values).
     *
     * @return array<string>
     */
    public static function staticClasses(): array
    {
        $path = dirname(__DIR__, 2) . '/resources/css/safelist-classes.txt';

        if (! file_exists($path)) {
            return [];
        }

        return array_values(array_filter(
            array_map(trim(...), file($path, FILE_IGNORE_NEW_LINES)),
        ));
    }

    /**
     * Collect all unique CSS classes from a collection of pages.
     *
     * @param  iterable<Page>  $pages
     * @return array<string>
     */
    public static function classesForPages(iterable $pages): array
    {
        $classes = [];

        foreach ($pages as $page) {
            $classes = array_merge($classes, static::classesFromContent($page->content));
        }

        return array_values(array_unique($classes));
    }

    /**
     * Extract all Tailwind classes from a page content array.
     *
     * @return array<string>
     */
    public static function classesFromContent(?array $content): array
    {
        if (! $content || empty($content['rows'])) {
            return [];
        }

        $classes = [];

        foreach ($content['rows'] as $row) {
            $classes = array_merge($classes, static::classesFromRow($row));
        }

        return array_values(array_unique($classes));
    }

    /**
     * Get user-defined custom classes from a row and its children.
     * Static plugin classes (flex, w-*, gap-*) are covered by staticClasses().
     */
    protected static function classesFromRow(array $row): array
    {
        $classes = [];
        $settings = $row['settings'] ?? [];

        // User-added custom gap class (if not default)
        if (! empty($settings['gap'])) {
            $classes[] = $settings['gap'];
        }

        // User-added classes via Advanced tab
        if (! empty($settings['class'])) {
            $classes = array_merge($classes, static::splitClasses($settings['class']));
        }

        foreach ($row['columns'] ?? [] as $column) {
            $classes = array_merge($classes, static::classesFromColumn($column));
        }

        return $classes;
    }

    /**
     * Get user-defined custom classes from a column and its widgets.
     */
    protected static function classesFromColumn(array $column): array
    {
        $classes = [];
        $settings = $column['settings'] ?? [];

        // User-added classes via Advanced tab
        if (! empty($settings['class'])) {
            $classes = array_merge($classes, static::splitClasses($settings['class']));
        }

        foreach ($column['widgets'] ?? [] as $widget) {
            $classes = array_merge($classes, static::classesFromWidget($widget));
        }

        return $classes;
    }

    /**
     * Get user-defined custom classes from a widget's data.
     */
    protected static function classesFromWidget(array $widget): array
    {
        $classes = [];
        $data = $widget['data'] ?? [];

        // User-added classes via Advanced tab
        if (! empty($data['class'])) {
            return array_merge($classes, static::splitClasses($data['class']));
        }

        return $classes;
    }

    /**
     * Split a class string into individual class names.
     *
     * @return array<string>
     */
    protected static function splitClasses(string $classString): array
    {
        return array_values(array_filter(
            preg_split('/\s+/', trim($classString)),
        ));
    }

    /**
     * Get all inline CSS declarations used across all published pages.
     * Useful for auditing what custom styles are in use.
     *
     * @return array<string>
     */
    public static function inlineStyles(): array
    {
        $modelClass = config('layup.pages.model', Page::class);
        $styles = [];

        foreach ($modelClass::published()->get() as $page) {
            $styles = array_merge($styles, static::inlineStylesFromContent($page->content));
        }

        return array_values(array_unique($styles));
    }

    /**
     * Extract all inline_css values from page content.
     *
     * @return array<string>
     */
    public static function inlineStylesFromContent(?array $content): array
    {
        if (! $content || empty($content['rows'])) {
            return [];
        }

        $styles = [];

        foreach ($content['rows'] as $row) {
            if (! empty($row['settings']['inline_css'])) {
                $styles[] = $row['settings']['inline_css'];
            }

            foreach ($row['columns'] ?? [] as $column) {
                if (! empty($column['settings']['inline_css'])) {
                    $styles[] = $column['settings']['inline_css'];
                }

                foreach ($column['widgets'] ?? [] as $widget) {
                    if (! empty($widget['data']['inline_css'])) {
                        $styles[] = $widget['data']['inline_css'];
                    }
                }
            }
        }

        return $styles;
    }

    /**
     * Generate a safelist string suitable for writing to a file.
     * One class per line — can be used as a Tailwind v4 @source file
     * or added to v3's safelist config.
     */
    public static function toSafelistFile(): string
    {
        $classes = static::classes();
        sort($classes);

        return implode("\n", $classes) . "\n";
    }

    /**
     * Sync the safelist and dispatch SafelistChanged if classes changed.
     *
     * Uses Laravel's cache (any driver) to track the previous class list.
     * Optionally writes the safelist file — if the filesystem is read-only
     * or the write fails, the event still fires so listeners can react.
     *
     * Returns true if the safelist changed, false otherwise.
     */
    public static function sync(?string $path = null): bool
    {
        $path ??= static::defaultPath();

        $newClasses = static::classes();
        sort($newClasses);

        // Compare against cached hash to avoid unnecessary work
        $newHash = md5(implode("\n", $newClasses));
        $cacheKey = 'layup:safelist:hash';
        $oldHash = cache()->get($cacheKey);

        if ($oldHash === $newHash) {
            return false;
        }

        // Determine what changed
        $oldClasses = cache()->get('layup:safelist:classes', []);
        $added = array_values(array_diff($newClasses, $oldClasses));
        $removed = array_values(array_diff($oldClasses, $newClasses));

        // Update cache (works with any driver — file, redis, array, etc.)
        cache()->put($cacheKey, $newHash);
        cache()->put('layup:safelist:classes', $newClasses);

        // Write the file (best-effort — don't break the save if filesystem is read-only)
        static::writeFile($path, $newClasses);

        SafelistChanged::dispatch($added, $removed, $path);

        return true;
    }

    /**
     * Write the safelist file. Silently fails if the path is not writable.
     *
     * @param  array<string>  $classes
     */
    protected static function writeFile(string $path, array $classes): bool
    {
        try {
            $dir = dirname($path);
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            file_put_contents($path, implode("\n", $classes) . "\n");

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Get the default safelist file path.
     */
    public static function defaultPath(): string
    {
        return base_path(config('layup.safelist.path', 'storage/layup-safelist.txt'));
    }
}
