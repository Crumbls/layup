<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Models\Page;

/**
 * Renders the current page's title as an h1.
 *
 * The author drops the widget; the heading text and level are injected at
 * render time from the page being rendered. Reuses the heading Blade view
 * so styling stays consistent with the standard heading widget.
 */
class PageTitleWidget extends HeadingWidget
{
    public static function getType(): string
    {
        return 'page-title';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.page-title');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-h1';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [];
    }

    public static function getDefaultData(): array
    {
        return [
            'level' => 'h1',
        ];
    }

    public static function getPreview(array $data): string
    {
        return __('layup::widgets.page-title.preview');
    }

    public static function prepareForRender(array $data): array
    {
        $data['level'] = 'h1';
        $data['content'] = self::resolvePageTitle();

        return $data;
    }

    protected function getViewName(): string
    {
        return 'layup::components.heading';
    }

    private static function resolvePageTitle(): string
    {
        $modelClass = config('layup.pages.model', Page::class);

        $path = (string) (request()->route('slug') ?? '');

        if ($path === '') {
            $path = (string) (config('layup.pages.default_slug') ?? '');
        }

        $page = $modelClass::query()
            ->where('path', $path)
            ->first();

        return (string) ($page?->title ?? '');
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
