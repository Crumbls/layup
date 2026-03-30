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

    protected array $darkColors = [];

    protected array $fonts = [];

    protected ?string $borderRadius = null;

    public function colors(array $colors): static
    {
        $this->colors = array_merge($this->colors, $colors);

        return $this;
    }

    public function darkColors(array $colors): static
    {
        $this->darkColors = array_merge($this->darkColors, $colors);

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

    /**
     * @return array<string, string>
     */
    public function getDarkColors(): array
    {
        $dark = [];

        foreach ($this->colors as $name => $hex) {
            $dark[$name] = $this->darkColors[$name] ?? $this->lightenForDark($hex);
        }

        return $dark;
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

        foreach ($this->colors as $name => $value) {
            $contrast = $this->contrastColor($value);
            $lines[] = "    --layup-on-{$name}: {$contrast};";
        }

        $lines[] = '    --layup-surface: #ffffff;';
        $lines[] = '    --layup-on-surface: #111827;';
        $lines[] = '    --layup-border: #e5e7eb;';
        $lines[] = '    --layup-muted: #6b7280;';

        if ($this->borderRadius !== null) {
            $lines[] = "    --layup-radius: {$this->borderRadius};";
        }

        foreach ($this->fonts as $name => $value) {
            $lines[] = "    --layup-font-{$name}: {$value};";
        }

        $lines[] = '}';
        $lines[] = '';

        $darkColors = $this->getDarkColors();
        $lines[] = '.dark {';

        foreach ($darkColors as $name => $value) {
            $lines[] = "    --layup-{$name}: {$value};";
        }

        foreach ($darkColors as $name => $value) {
            $contrast = $this->contrastColor($value);
            $lines[] = "    --layup-on-{$name}: {$contrast};";
        }

        $lines[] = '    --layup-surface: #1f2937;';
        $lines[] = '    --layup-on-surface: #f9fafb;';
        $lines[] = '    --layup-border: #374151;';
        $lines[] = '    --layup-muted: #9ca3af;';

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
     * Return black or white depending on which contrasts better against the given color.
     */
    protected function contrastColor(string $hex): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (! preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            return '#ffffff';
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $luminance = ($r * 299 + $g * 587 + $b * 114) / 1000;

        return $luminance > 128 ? '#000000' : '#ffffff';
    }

    /**
     * Auto-lighten a hex color for dark mode by boosting its HSL lightness.
     */
    protected function lightenForDark(string $hex): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;

        if ($max === $min) {
            $h = $s = 0.0;
        } else {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

            $h = match ($max) {
                $r => (($g - $b) / $d + ($g < $b ? 6 : 0)) / 6,
                $g => (($b - $r) / $d + 2) / 6,
                default => (($r - $g) / $d + 4) / 6,
            };
        }

        $targetL = min(0.85, $l + 0.25);

        return $this->hslToHex($h, $s, $targetL);
    }

    protected function hslToHex(float $h, float $s, float $l): string
    {
        if ($s === 0.0) {
            $r = $g = $b = $l;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            $r = $this->hueToRgb($p, $q, $h + 1 / 3);
            $g = $this->hueToRgb($p, $q, $h);
            $b = $this->hueToRgb($p, $q, $h - 1 / 3);
        }

        return sprintf('#%02x%02x%02x', (int) round($r * 255), (int) round($g * 255), (int) round($b * 255));
    }

    protected function hueToRgb(float $p, float $q, float $t): float
    {
        if ($t < 0) {
            $t += 1;
        }

        if ($t > 1) {
            $t -= 1;
        }

        if ($t < 1 / 6) {
            return $p + ($q - $p) * 6 * $t;
        }

        if ($t < 1 / 2) {
            return $q;
        }

        if ($t < 2 / 3) {
            return $p + ($q - $p) * (2 / 3 - $t) * 6;
        }

        return $p;
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
