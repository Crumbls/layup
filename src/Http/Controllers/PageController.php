<?php

declare(strict_types=1);

namespace Crumbls\Layup\Http\Controllers;

use Crumbls\Layup\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

        return $modelClass::query()
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();
    }
}
