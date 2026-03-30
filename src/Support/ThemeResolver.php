<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

use Crumbls\Layup\LayupPlugin;
use Filament\Facades\Filament;

class ThemeResolver
{
    protected static bool $booted = false;

    public static function ensureBooted(): void
    {
        if (static::$booted) {
            return;
        }

        static::$booted = true;

        $theme = app(LayupTheme::class);

        if ($theme->getColors() !== (new LayupTheme)->getColors()) {
            return;
        }

        try {
            foreach (Filament::getPanels() as $panel) {
                foreach ($panel->getPlugins() as $plugin) {
                    if ($plugin instanceof LayupPlugin) {
                        $plugin->boot($panel);

                        return;
                    }
                }
            }
        } catch (\Throwable) {
            // Panels not available.
        }
    }
}
