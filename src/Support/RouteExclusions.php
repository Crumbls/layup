<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

use Filament\Facades\Filament;

/**
 * Builds the list of top-level path segments that the Layup
 * catch-all route must never match when running at the site root.
 */
class RouteExclusions
{
    /**
     * Gather all paths that should be excluded from the catch-all.
     *
     * @return array<string>
     */
    public static function gather(): array
    {
        return array_values(array_unique(array_merge(
            static::filamentPanelPaths(),
            static::frameworkPaths(),
            config('layup.frontend.excluded_paths', []),
        )));
    }

    /**
     * Detect every registered Filament panel path.
     *
     * @return array<string>
     */
    protected static function filamentPanelPaths(): array
    {
        $paths = [];

        try {
            foreach (Filament::getPanels() as $panel) {
                $path = trim($panel->getPath(), '/');

                if ($path !== '') {
                    $paths[] = explode('/', $path)[0];
                }
            }
        } catch (\Throwable) {
            // Filament not booted yet or no panels registered.
        }

        return $paths;
    }

    /**
     * Framework paths that should never be caught by a root-level wildcard.
     *
     * @return array<string>
     */
    protected static function frameworkPaths(): array
    {
        return [
            'livewire',
            'filament',
            'storage',
            'sanctum',
            'api',
            '_debugbar',
            '_ignition',
            'telescope',
            'horizon',
            'pulse',
            'up',
        ];
    }
}
