<?php

declare(strict_types=1);

namespace Crumbls\Layup\Http\Controllers;

use Crumbls\Layup\Support\Concerns\RegistersWidgets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Base controller for rendering Layup content on the frontend.
 *
 * Model-agnostic: subclasses resolve any Eloquent model from the
 * request. The model must implement `getSectionTree()` and
 * `getContentTree()` (as Page does).
 *
 * For a Page-specific implementation, see AbstractPageController.
 *
 * Minimal implementation:
 *
 *     class PostController extends AbstractController
 *     {
 *         protected function getRecord(Request $request): Model
 *         {
 *             return Post::where('slug', $request->route('slug'))
 *                 ->firstOrFail();
 *         }
 *     }
 *
 * @see \Crumbls\Layup\Http\Controllers\AbstractPageController
 * @see \Crumbls\Layup\Support\WidgetRegistry
 */
abstract class AbstractController extends Controller
{
    use RegistersWidgets;

    public function __invoke(Request $request): Response
    {
        $this->ensureWidgetsRegistered();

        $record = $this->getRecord($request);
        $this->authorize($request, $record);

        $sections = $record->getSectionTree();
        $tree = $record->getContentTree();
        $layout = $this->getLayout($request, $record);

        $viewData = array_merge([
            'page' => $record,
            'record' => $record,
            'sections' => $sections,
            'tree' => $tree,
            'layout' => $layout,
        ], $this->getViewData($request, $record, $sections));

        $response = response()->view($this->getView($request, $record), $viewData);

        $ttl = $this->getCacheTtl($request, $record);

        if ($ttl !== null) {
            $response->header('Cache-Control', "public, max-age={$ttl}");
        }

        return $response;
    }

    /**
     * Resolve the model record for the current request.
     *
     * Return any Eloquent model that implements getSectionTree()
     * and getContentTree(). Use ->firstOrFail() for automatic 404s.
     */
    abstract protected function getRecord(Request $request): Model;

    /**
     * Authorize access to the resolved record.
     *
     * Called after the record is resolved but before rendering.
     * Throw or abort to deny access. Does nothing by default.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function authorize(Request $request, Model $record): void
    {
        //
    }

    /**
     * Determine the Blade layout component wrapping the content.
     *
     * The returned string is passed to `<x-dynamic-component>`.
     * Override to implement per-record or per-type layout logic.
     *
     * @see config('layup.frontend.layout')
     */
    protected function getLayout(Request $request, Model $record): string
    {
        return config('layup.frontend.layout', 'layup::layouts.page');
    }

    /**
     * Determine which Blade view to render.
     *
     * Override to implement view fallback chains, slug-based
     * view resolution, or any other strategy.
     *
     * @see config('layup.frontend.view')
     */
    protected function getView(Request $request, Model $record): string
    {
        return config('layup.frontend.view', 'layup::frontend.page');
    }

    /**
     * Provide additional data to the view.
     *
     * Merged with the base data (`page`, `record`, `sections`, `tree`, `layout`).
     * Return an associative array of any extra variables.
     *
     * @param  array<int, array{settings: array<string, mixed>, rows: array<\Crumbls\Layup\View\Row>}>  $sections
     * @return array<string, mixed>
     */
    protected function getViewData(Request $request, Model $record, array $sections): array
    {
        return [];
    }

    /**
     * Return a cache TTL in seconds, or null to skip caching headers.
     *
     * When non-null, a `Cache-Control: public, max-age={ttl}` header
     * is added to the response. Pair with a reverse proxy or
     * Laravel's response cache for full-page caching.
     */
    protected function getCacheTtl(Request $request, Model $record): ?int
    {
        return null;
    }
}
