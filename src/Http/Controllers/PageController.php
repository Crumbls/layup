<?php

declare(strict_types=1);

namespace Crumbls\Layup\Http\Controllers;

use Crumbls\Layup\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Default invokable controller that resolves pages by their full URL path.
 *
 * Supports:
 *  - Top-level pages: /pages/about       (path = "about")
 *  - Nested pages:    /pages/docs/intro  (path = "docs/intro")
 *  - Configurable model class via config('layup.pages.model')
 */
class PageController extends AbstractController
{
    protected function getRecord(Request $request): Model
    {
        $modelClass = config('layup.pages.model', Page::class);

        $path = $request->route('slug', '');

        if ($path === '' || $path === null) {
            $path = config('layup.pages.default_slug') ?? '';
        }

        $page = $modelClass::query()
            ->where('path', $path)
            ->published()
            ->first();

        if ($page) {
            return $page;
        }

        // In debug mode, check if a draft exists and hint at the cause
        if (app()->hasDebugModeEnabled()) {
            $draft = $modelClass::query()
                ->where('path', $path)
                ->where('status', 'draft')
                ->exists();

            if ($draft) {
                throw new NotFoundHttpException(
                    "Layup: Page '{$path}' exists but is in draft status. Publish it in the admin panel to make it visible."
                );
            }
        }

        throw new NotFoundHttpException;
    }

    /**
     * Per-page template override: page->meta.layout.template wins, then
     * the global default_template, then the legacy frontend.view config.
     * An unknown template name falls back rather than throwing so a
     * deleted/renamed view doesn't 500 the page.
     */
    protected function getView(Request $request, Model $record): string
    {
        $templates = (array) config('layup.page_layout.templates', []);
        $key = $record->meta['layout']['template'] ?? null;

        if ($key && array_key_exists($key, $templates) && View::exists($key)) {
            return $key;
        }

        $default = config('layup.page_layout.default_template');

        if ($default && View::exists($default)) {
            return $default;
        }

        return parent::getView($request, $record);
    }
}
