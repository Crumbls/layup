<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support\Concerns;

use Crumbls\Layup\Support\WidgetRegistry;
use Crumbls\Layup\View\BaseWidget;

/**
 * Populates the WidgetRegistry from config and auto-discovery.
 *
 * In a Filament panel, LayupPlugin handles registration.
 * On frontend routes, controllers and helpers use this trait.
 */
trait RegistersWidgets
{
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

            if (class_exists($className) && is_subclass_of($className, BaseWidget::class) && ! $registry->has($className::getType())) {
                $registry->register($className);
            }
        }
    }
}
