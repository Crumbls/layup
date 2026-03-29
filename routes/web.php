<?php

declare(strict_types=1);

use Crumbls\Layup\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Layup Frontend Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by LayupServiceProvider when frontend routing is
| enabled (layup.frontend.enabled = true). The route prefix, middleware,
| and domain are all configurable.
|
| The wildcard {slug} captures nested paths like "docs/getting-started"
| so pages can use hierarchical slugs.
|
*/

$prefix = config('layup.frontend.prefix', 'pages');
$middleware = config('layup.frontend.middleware', ['web']);
$domain = config('layup.frontend.domain');
$route = Route::middleware($middleware);

if ($domain) {
    $route = $route->domain($domain);
}

$route->group(function () use ($prefix) {
    // Exact prefix match → homepage/index page (slug = '')
    Route::get($prefix, PageController::class)
        ->name('layup.page.index');

    // Build a negative-lookahead so the catch-all never swallows
    // framework or Filament panel paths when running at the root.
    $slugPattern = '.*';

    if ($prefix === '' || $prefix === '/') {
        $excluded = \Crumbls\Layup\Support\RouteExclusions::gather();

        if ($excluded !== []) {
            $escaped = array_map(fn (string $path): string => preg_quote($path, '/'), $excluded);
            $slugPattern = '(?!' . implode('|', $escaped) . ').*';
        }
    }

    // Wildcard catch-all for nested slugs
    Route::get("{$prefix}/{slug}", PageController::class)
        ->where('slug', $slugPattern)
        ->name('layup.page.show');
});
