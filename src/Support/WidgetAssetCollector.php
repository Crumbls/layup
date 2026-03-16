<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

class WidgetAssetCollector
{
    /**
     * Collect deduplicated JS and CSS assets from all widgets in a content structure.
     *
     * @return array{js: array<string>, css: array<string>}
     */
    public static function fromContent(array $content): array
    {
        $js = [];
        $css = [];

        ContentWalker::walkWidgets($content, function (string $type) use (&$js, &$css): void {
            $registry = app(WidgetRegistry::class);
            $class = $registry->get($type);

            if (! $class) {
                return;
            }

            $assets = $class::getAssets();

            foreach ($assets['js'] ?? [] as $src) {
                $js[$src] = $src;
            }

            foreach ($assets['css'] ?? [] as $href) {
                $css[$href] = $href;
            }
        });

        return ['js' => array_values($js), 'css' => array_values($css)];
    }
}
