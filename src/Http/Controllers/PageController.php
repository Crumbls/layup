<?php

declare(strict_types=1);

namespace Crumbls\Layup\Http\Controllers;

use Crumbls\Layup\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Default invokable controller that resolves pages by slug.
 *
 * Supports:
 *  - Exact slug match: /pages/about
 *  - Nested/wildcard slugs: /pages/docs/getting-started
 *  - Configurable model class via config('layup.pages.model')
 */
class PageController extends AbstractController
{
    protected function getRecord(Request $request): Model
    {
        $modelClass = config('layup.pages.model', Page::class);

        $slug = $request->route('slug', '');

        if ($slug === '' || $slug === null) {
            $slug = config('layup.pages.default_slug') ?? '';
        }

        $page = $modelClass::query()
            ->where('slug', $slug)
            ->published()
            ->first();

        if ($page) {
            return $page;
        }

        // In debug mode, check if a draft exists and hint at the cause
        if (app()->hasDebugModeEnabled()) {
            $draft = $modelClass::query()
                ->where('slug', $slug)
                ->where('status', 'draft')
                ->exists();

            if ($draft) {
                throw new NotFoundHttpException(
                    "Layup: Page '{$slug}' exists but is in draft status. Publish it in the admin panel to make it visible."
                );
            }
        }

        throw new NotFoundHttpException;
    }
}
