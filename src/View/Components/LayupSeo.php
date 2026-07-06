<?php

declare(strict_types=1);

namespace Crumbls\Layup\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

/**
 * Drop-in SEO meta component. Hosts add `<x-layup-seo />`once in their
 * layout's <head>; on Layup-rendered requests the component emits the
 * full meta block (OG, Twitter, canonical, JSON-LD), and on every
 * other request it renders nothing.
 *
 * Resolution order for the page:
 *   1. an explicit :page="$something" prop, or
 *   2. the `layupPage` view-shared variable populated by AbstractController.
 *
 * This sidesteps Blade's component-scope isolation: layout components
 * don't inherit parent variables, so a slot- or attribute-based
 * contract would force every host to wire the page through manually.
 */
class LayupSeo extends Component
{
    public ?Model $page;

    public function __construct(?Model $page = null)
    {
        $this->page = $page ?? view()->shared('layupPage');
    }

    public function shouldRender(): bool
    {
        return $this->page !== null;
    }

    public function render(): View
    {
        return view('layup::components.layup-seo');
    }
}
