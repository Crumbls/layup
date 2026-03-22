<?php

declare(strict_types=1);

namespace Crumbls\Layup;

use Crumbls\Layup\Contracts\Widget;
use Crumbls\Layup\Resources\PageResource;
use Crumbls\Layup\Support\WidgetRegistry;
use Filament\Contracts\Plugin;
use Filament\Panel;

class LayupPlugin implements Plugin
{
    /** @var array<class-string<Widget>> Extra widgets registered via the plugin constructor */
    protected array $extraWidgets = [];

    /** @var array<class-string<Widget>> Widget types to remove from the registry */
    protected array $removedWidgets = [];

    /** @var bool Whether to load widgets from config */
    protected bool $useConfigWidgets = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'layup';
    }

    /**
     * Register additional widget classes.
     *
     * Usage in AdminPanelProvider:
     *   LayupPlugin::make()->widgets([MyCustomWidget::class, AnotherWidget::class])
     *
     * @param  array<class-string<Widget>>  $widgets
     */
    public function widgets(array $widgets): static
    {

        $this->extraWidgets = array_merge($this->extraWidgets, $widgets);

        return $this;
    }

    /**
     * Remove specific widget types from the registry.
     *
     * Usage:
     *   LayupPlugin::make()->withoutWidgets([TextWidget::class, ButtonWidget::class])
     *
     * @param  array<class-string<Widget>>  $widgets  Widget classes to remove
     */
    public function withoutWidgets(array $widgets): static
    {
        $this->removedWidgets = array_merge($this->removedWidgets, $widgets);

        return $this;
    }

    /**
     * Skip loading widgets from config (only use those passed via widgets()).
     */
    public function withoutConfigWidgets(): static
    {
        $this->useConfigWidgets = false;

        return $this;
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            PageResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        $registry = app(WidgetRegistry::class);

        // Register config widgets (unless disabled)
        if ($this->useConfigWidgets) {
            foreach (config('layup.widgets', []) as $widget) {
                $registry->register($widget);
            }
        }

        // Register plugin-constructor widgets
        foreach ($this->extraWidgets as $widget) {
            $registry->register($widget);
        }

        // Remove excluded widgets
        foreach ($this->removedWidgets as $type) {
            if (is_subclass_of($type, Widget::class)) {
                $registry->unregister($type::getType());
            } else {
                $registry->unregister($type);
            }
        }
    }
}
