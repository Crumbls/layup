---
title: Embedding the Field
weight: 3
nav_title: Embedding the Field
order: 30
---

`LayupBuilder` is a Filament form field. Drop it into any form on any model that has a JSON column, and editors get a Divi-style canvas with rows, columns, breakpoint previews, and 96 widgets. The Pages resource that ships with Layup is one application of this field -- everything you can do there, you can do on your own models.

This guide walks through wiring the field onto an existing Eloquent model.

## What you need

- A Filament 5 panel with `LayupPlugin` registered (see [Installation](installation.md))
- An Eloquent model with a JSON column to hold the layout (any name -- `content`, `body`, `layout`, etc.)
- A Filament resource (or page, or relation manager form) where you want the editor to appear

## Step 1: Add a JSON column to your model

If your model does not yet have a column to store layout content, add one:

```php
// database/migrations/2026_05_02_000000_add_layup_to_posts.php
public function up(): void
{
    Schema::table('posts', function (Blueprint $table) {
        $table->json('content')->nullable();
    });
}
```

Run the migration:

```bash
php artisan migrate
```

## Step 2: Cast the column and add the trait

Cast the column to `array` so Eloquent serializes it. Add the `HasLayupContent` trait so you can render the content with one call:

```php
namespace App\Models;

use Crumbls\Layup\Concerns\HasLayupContent;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasLayupContent;

    protected $fillable = ['title', 'content'];

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }
}
```

The trait defaults to a column named `content`. If your column has a different name, set `$layupContentColumn`:

```php
class Post extends Model
{
    use HasLayupContent;

    protected string $layupContentColumn = 'body';
}
```

## Step 3: Add the field to your Filament form

Drop `LayupBuilder::make()` into your resource form. The field auto-applies `columnSpanFull()`, so the canvas always uses the full form width:

```php
namespace App\Filament\Resources;

use App\Models\Post;
use Crumbls\Layup\Forms\Components\LayupBuilder;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->required(),
            LayupBuilder::make('content'),
        ]);
    }
}
```

That is the entire integration. Save a record and the layout JSON is persisted to the column. No additional configuration, no Pages resource needed.

## Step 4: Render the content

You have three rendering options. Pick the one that fits your view layer.

### Option A: The `@layup` Blade directive

The shortest path. Pass any Layup content array and Layup walks the tree:

```blade
{{-- resources/views/posts/show.blade.php --}}
<article>
    <h1>{{ $post->title }}</h1>
    @layup($post->content)
</article>
```

### Option B: The `HasLayupContent` trait's `toHtml()`

Renders to a string -- useful for caching, mail, or anywhere you want the HTML as data:

```blade
<article>
    <h1>{{ $post->title }}</h1>
    {!! $post->toHtml() !!}
</article>
```

### Option C: The `<x-layup-widget>` component

Render a single widget without the full row/column tree -- handy for partials and email templates:

```blade
<x-layup-widget type="button" :data="['label' => 'Read more', 'url' => $post->url]" />
```

For the full rendering surface (route helpers, scripts directive, render isolation), see [Rendering content](customization/rendering-content.md).

## Multiple models, multiple fields

You can use `LayupBuilder` on as many models and fields as you like. Each model just needs its own JSON column and (optionally) the `HasLayupContent` trait. A single application can have:

- A `Post` model with a `content` column
- A `LandingPage` model with a `hero` and a `body` column (two `LayupBuilder` instances on the same form)
- The bundled Layup `Page` model with its own table

All four fields share the same widget registry, theme, and Tailwind safelist.

## Tailwind safelist still applies

The field generates dynamic CSS classes (column widths, spacing, custom styles) regardless of which model stores the content. You still need to configure the Tailwind safelist exactly as documented in [Installation](installation.md#tailwind-safelist-setup), even on a field-only setup.

If you are not using the Pages resource, the auto-sync hook does not fire on your model's saves out of the box. Either run `php artisan layup:safelist` from your build pipeline, or hook a model event:

```php
// app/Providers/AppServiceProvider.php
use App\Models\Post;
use Crumbls\Layup\Support\SafelistCollector;

public function boot(): void
{
    Post::saved(function (Post $post): void {
        SafelistCollector::sync();
    });
}
```

## What if I do not want the Pages resource at all?

See [Field-only installation](field-only-installation.md) for the minimal install path that disables the bundled Pages resource entirely.

## Where to go next

- [Field-only installation](field-only-installation.md) -- skip the Pages resource
- [Rendering content](customization/rendering-content.md) -- every render mechanism in detail
- [Custom widgets](customization/custom-widgets.md) -- build your own widgets for the picker
- [Configuration](configuration.md) -- every config key explained
