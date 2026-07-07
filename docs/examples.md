---
title: Examples
nav_title: Examples
weight: 40
order: 50
---

# Examples

These examples use public Layup APIs verified from the package source.

## Add Layup to a custom model

Create a JSON column:

```php
Schema::table('posts', function (Blueprint $table): void {
    $table->json('content')->nullable();
});
```

Cast the column and add `HasLayupContent`:

```php
namespace App\Models;

use Crumbls\Layup\Concerns\HasLayupContent;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasLayupContent;

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }
}
```

Add the builder to a Filament resource form:

```php
use Crumbls\Layup\Forms\Components\LayupBuilder;

LayupBuilder::make('content')
    ->columnSpanFull();
```

Render the saved content:

```blade
{!! $post->toHtml() !!}
```

## Register only selected widgets

```php
use Crumbls\Layup\LayupPlugin;
use Crumbls\Layup\View\HeadingWidget;
use Crumbls\Layup\View\ImageWidget;
use Crumbls\Layup\View\TextWidget;

LayupPlugin::make()
    ->withoutConfigWidgets()
    ->widgets([
        HeadingWidget::class,
        TextWidget::class,
        ImageWidget::class,
    ]);
```

Use `withoutWidgets()` when you want the configured widget list except for a few classes:

```php
use Crumbls\Layup\View\HtmlWidget;
use Crumbls\Layup\View\LoginWidget;

LayupPlugin::make()
    ->withoutWidgets([
        HtmlWidget::class,
        LoginWidget::class,
    ]);
```

## Render published pages from your own controller

```php
namespace App\Http\Controllers;

use Crumbls\Layup\Models\Page;
use Illuminate\View\View;

class MarketingPageController
{
    public function show(string $path): View
    {
        $model = config('layup.pages.model', Page::class);

        $page = $model::query()
            ->published()
            ->where('path', $path)
            ->firstOrFail();

        return view('marketing.page', ['page' => $page]);
    }
}
```

Render in `resources/views/marketing/page.blade.php`:

```blade
<x-layouts.app :title="$page->getMetaTitle()">
    <x-layup-seo :page="$page" />

    {!! $page->toHtml() !!}
</x-layouts.app>
```

## Test a custom widget

```php
use App\Layup\Widgets\PromoStripWidget;
use Crumbls\Layup\Testing\LayupAssertions;

uses(LayupAssertions::class);

it('satisfies the widget contract', function (): void {
    $this->assertWidgetContractValid(PromoStripWidget::class);
});

it('renders with default data', function (): void {
    $this->assertWidgetRendersWithDefaults(PromoStripWidget::class);
});
```
