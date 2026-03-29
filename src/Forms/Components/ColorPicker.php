<?php

declare(strict_types=1);

namespace Crumbls\Layup\Forms\Components;

use Crumbls\Layup\Support\LayupTheme;
use Filament\Forms\Components\Field;

class ColorPicker extends Field
{
    protected string $view = 'layup::forms.components.color-picker';

    protected ?array $swatches = null;

    protected bool $allowCustom = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->nullable();
    }

    /**
     * Override the default theme swatches with explicit colors.
     *
     * @param  array<string, string>  $swatches  e.g. ['Red' => '#ef4444', 'Blue' => '#3b82f6']
     */
    public function swatches(array $swatches): static
    {
        $this->swatches = $swatches;

        return $this;
    }

    public function allowCustom(bool $allow = true): static
    {
        $this->allowCustom = $allow;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getSwatches(): array
    {
        if ($this->swatches !== null) {
            return $this->swatches;
        }

        return app(LayupTheme::class)->getColors();
    }

    public function getAllowCustom(): bool
    {
        return $this->allowCustom;
    }

    public function getContrastColor(string $hex): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $luminance = ($r * 299 + $g * 587 + $b * 114) / 1000;

        return $luminance > 128 ? '#000000' : '#ffffff';
    }
}
