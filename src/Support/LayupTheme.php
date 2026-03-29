<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

class LayupTheme
{
    protected array $colors = [
        'primary' => '#3b82f6',
        'secondary' => '#6b7280',
        'accent' => '#f59e0b',
        'success' => '#22c55e',
        'warning' => '#f59e0b',
        'danger' => '#ef4444',
    ];

    protected array $fonts = [];

    protected ?string $borderRadius = null;

    public function colors(array $colors): static
    {
        $this->colors = array_merge($this->colors, $colors);

        return $this;
    }

    public function fonts(array $fonts): static
    {
        $this->fonts = array_merge($this->fonts, $fonts);

        return $this;
    }

    public function borderRadius(?string $radius): static
    {
        $this->borderRadius = $radius;

        return $this;
    }

    public function getColors(): array
    {
        return $this->colors;
    }

    public function getColor(string $name): ?string
    {
        return $this->colors[$name] ?? null;
    }

    public function getFonts(): array
    {
        return $this->fonts;
    }

    public function getBorderRadius(): ?string
    {
        return $this->borderRadius;
    }

    /**
     * Generate the full CSS string: custom properties + utility classes.
     */
    public function toCss(): string
    {
        $lines = [':root {'];

        foreach ($this->colors as $name => $value) {
            $lines[] = "    --layup-{$name}: {$value};";
        }

        if ($this->borderRadius !== null) {
            $lines[] = "    --layup-radius: {$this->borderRadius};";
        }

        foreach ($this->fonts as $name => $value) {
            $lines[] = "    --layup-font-{$name}: {$value};";
        }

        $lines[] = '}';
        $lines[] = '';

        foreach ($this->colors as $name => $value) {
            $lines[] = ".layup-bg-{$name} { background-color: var(--layup-{$name}); }";
            $lines[] = ".layup-text-{$name} { color: var(--layup-{$name}); }";
            $lines[] = ".layup-border-{$name} { border-color: var(--layup-{$name}); }";
            $lines[] = ".layup-hover-bg-{$name}:hover { background-color: var(--layup-{$name}); }";
            $lines[] = ".layup-hover-text-{$name}:hover { color: var(--layup-{$name}); }";
        }

        if ($this->borderRadius !== null) {
            $lines[] = '.layup-rounded { border-radius: var(--layup-radius); }';
        }

        foreach ($this->fonts as $name => $value) {
            $lines[] = ".layup-font-{$name} { font-family: var(--layup-font-{$name}); }";
        }

        return implode("\n", $lines);
    }

    /**
     * Build a class name for a given property and color.
     *
     * e.g. className('bg', 'primary') => 'layup-bg-primary'
     */
    public static function className(string $property, string $name): string
    {
        return "layup-{$property}-{$name}";
    }

    /**
     * Return all generated class names for safelist integration.
     *
     * @return array<string>
     */
    public function getSafelistClasses(): array
    {
        $classes = [];

        foreach (array_keys($this->colors) as $name) {
            $classes[] = "layup-bg-{$name}";
            $classes[] = "layup-text-{$name}";
            $classes[] = "layup-border-{$name}";
            $classes[] = "layup-hover-bg-{$name}";
            $classes[] = "layup-hover-text-{$name}";
        }

        if ($this->borderRadius !== null) {
            $classes[] = 'layup-rounded';
        }

        foreach (array_keys($this->fonts) as $name) {
            $classes[] = "layup-font-{$name}";
        }

        return $classes;
    }
}
