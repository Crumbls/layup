<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

use Crumbls\Layup\View\BaseView;

/** Responsive declarations shared by the frontend and the builder canvas. */
class ResponsiveStyles
{
    public static function declarations(array $data): array
    {
        $result = [];
        if (! is_array($data['responsive'] ?? null)) {
            return $result;
        }
        $allowed = ['text_color', 'text_align', 'font_size', 'border_radius', 'border_width', 'border_style', 'border_color', 'box_shadow', 'opacity', 'background_color', 'padding', 'margin'];
        foreach (config('layup.breakpoints', []) as $key => $breakpoint) {
            $values = $data['responsive'][$key] ?? [];
            if (! is_array($values)) {
                continue;
            }
            $values = array_intersect_key($values, array_flip($allowed));
            foreach ($values as $field => $value) {
                if (in_array($field, ['padding', 'margin'], true)) {
                    if (! is_array($value) && ! is_numeric($value)) {
                        unset($values[$field]);
                    }
                } elseif (! is_scalar($value) || ! preg_match('/^[#\w\s.,()%+\/-]*$/D', (string) $value)) {
                    unset($values[$field]);
                }
            }
            $css = BaseView::buildInlineStyles($values);
            if (! empty($data['animation'])) {
                $css = preg_replace('/(^|;\s*)opacity:/', '$1--layup-responsive-opacity:', $css);
            }
            if ($css !== '') {
                $result[$key] = preg_replace('/;(?=\s|$)/', ' !important;', $css);
            }
        }

        return $result;
    }

    public static function signature(array $data): string
    {
        $declarations = self::declarations($data);

        return $declarations === [] ? '' : substr(hash('sha256', json_encode($declarations)), 0, 16);
    }

    public static function render(array $data): string
    {
        $declarations = self::declarations($data);
        if ($declarations === []) {
            return '';
        }
        $signature = self::signature($data);
        $selector = '[style*="--layup-style: ' . $signature . ';"]';
        $css = '';
        $inherited = '';
        foreach (config('layup.breakpoints', []) as $index => $breakpoint) {
            $inherited .= $declarations[$index] ?? '';
            $width = max(0, (int) ($breakpoint['width'] ?? 0));
            if (isset($declarations[$index])) {
                $rule = $selector . ':not(.lyp-wrap *){' . $declarations[$index] . '}';
                $css .= $index === array_key_first(config('layup.breakpoints', [])) ? $rule : '@media (min-width: ' . $width . 'px){' . $rule . '}';
            }
            // The canvas uses its selected breakpoint rather than the admin viewport.
            $safeKey = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $index);
            $css .= '.lyp-wrap[data-layup-breakpoint="' . $safeKey . '"] ' . $selector . '{' . $inherited . '}';
        }

        return '<style hidden data-layup-responsive>' . $css . '</style>';
    }
}
