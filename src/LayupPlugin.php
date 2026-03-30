<?php

declare(strict_types=1);

namespace Crumbls\Layup;

use Crumbls\Layup\Contracts\Widget;
use Crumbls\Layup\Resources\PageResource;
use Crumbls\Layup\Support\LayupTheme;
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

    /** @var array<string, string> Theme color overrides (name => hex) */
    protected array $themeColors = [];

    /** @var array<string, string> Dark mode color overrides (name => hex) */
    protected array $themeDarkColors = [];

    /** @var array<string, string> Theme font overrides (name => font-family) */
    protected array $themeFonts = [];

    protected ?string $themeBorderRadius = null;

    protected bool $inheritPanelColors = true;

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

    /**
     * Set theme colors. Merges with (and overrides) inherited panel colors.
     *
     * @param  array<string, string>  $colors  e.g. ['primary' => '#ff0000', 'accent' => '#00ff00']
     */
    public function colors(array $colors): static
    {
        $this->themeColors = array_merge($this->themeColors, $colors);

        return $this;
    }

    /**
     * Set dark mode color overrides. Colors not specified here are auto-lightened.
     *
     * @param  array<string, string>  $colors  e.g. ['primary' => '#fb7185']
     */
    public function darkColors(array $colors): static
    {
        $this->themeDarkColors = array_merge($this->themeDarkColors, $colors);

        return $this;
    }

    /**
     * Set theme fonts.
     *
     * @param  array<string, string>  $fonts  e.g. ['heading' => 'Playfair Display, serif', 'body' => 'Inter, sans-serif']
     */
    public function fonts(array $fonts): static
    {
        $this->themeFonts = array_merge($this->themeFonts, $fonts);

        return $this;
    }

    /**
     * Set the global default border radius.
     */
    public function borderRadius(string $radius): static
    {
        $this->themeBorderRadius = $radius;

        return $this;
    }

    /**
     * Do not inherit colors from the Filament panel.
     */
    public function withoutPanelColors(): static
    {
        $this->inheritPanelColors = false;

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
        $this->bootTheme($panel);
        $this->bootWidgets();
    }

    protected function bootTheme(Panel $panel): void
    {
        $theme = app(LayupTheme::class);

        if ($this->inheritPanelColors) {
            $panelColors = $this->extractPanelColors($panel);

            if ($panelColors !== []) {
                $theme->colors($panelColors);
            }
        }

        if ($this->themeColors !== []) {
            $theme->colors($this->themeColors);
        }

        if ($this->themeFonts !== []) {
            $theme->fonts($this->themeFonts);
        }

        if ($this->themeDarkColors !== []) {
            $theme->darkColors($this->themeDarkColors);
        }

        if ($this->themeBorderRadius !== null) {
            $theme->borderRadius($this->themeBorderRadius);
        }
    }

    protected function bootWidgets(): void
    {
        $registry = app(WidgetRegistry::class);

        if ($this->useConfigWidgets) {
            foreach (config('layup.widgets', []) as $widget) {
                $registry->register($widget);
            }
        }

        foreach ($this->extraWidgets as $widget) {
            $registry->register($widget);
        }

        foreach ($this->removedWidgets as $type) {
            if (is_subclass_of($type, Widget::class)) {
                $registry->unregister($type::getType());
            } else {
                $registry->unregister($type);
            }
        }
    }

    /**
     * Extract hex color values from the Filament panel's color config.
     *
     * @return array<string, string>
     */
    protected function extractPanelColors(Panel $panel): array
    {
        $colors = [];

        try {
            $panelColors = $panel->getColors();

            foreach ($panelColors as $name => $shades) {
                if (is_string($shades)) {
                    $colors[$name] = $shades;

                    continue;
                }

                if (is_array($shades) && isset($shades[500])) {
                    $colors[$name] = $this->rgbToHex($shades[500]);
                }
            }
        } catch (\Throwable) {
            // Panel colors not available -- use defaults.
        }

        return $colors;
    }

    /**
     * Convert an RGB string like "59, 130, 246" to hex "#3b82f6".
     */
    protected function rgbToHex(string $rgb): string
    {
        $parts = array_map('trim', explode(',', $rgb));

        if (count($parts) !== 3) {
            return $rgb;
        }

        return sprintf('#%02x%02x%02x', (int) $parts[0], (int) $parts[1], (int) $parts[2]);
    }
}
