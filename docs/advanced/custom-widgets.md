---
title: Creating Custom Widgets
weight: 1
---

## Scaffolding

Use the Artisan command to generate a new widget:

```bash
php artisan layup:make-widget BannerWidget
```

This creates two files:

- `app/Layup/Widgets/BannerWidget.php` -- the widget class
- `resources/views/components/layup/banner.blade.php` -- the Blade view

Add `--with-test` to also generate a Pest test file:

```bash
php artisan layup:make-widget BannerWidget --with-test
```

## Widget class structure

Every custom widget extends `BaseWidget` and implements two required methods:

```php
<?php

declare(strict_types=1);

namespace App\Layup\Widgets;

use Crumbls\Layup\View\BaseWidget;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

class BannerWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'banner';
    }

    public static function getLabel(): string
    {
        return 'Banner';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->required(),
            RichEditor::make('body')
                ->label('Body'),
            TextInput::make('button_url')
                ->label('Button URL')
                ->url(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'title' => 'Welcome',
            'body' => '',
            'button_url' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        return $data['title'] ?? '(empty)';
    }
}
```

## Required methods

### `getType(): string`

A unique kebab-case identifier stored in JSON content. Must not collide with built-in types.

### `getLabel(): string`

The display name shown in the widget picker.

## Optional overrides

### `getContentFormSchema(): array`

Return an array of Filament form components for the Content tab. This is where your widget's fields live.

### `getDefaultData(): array`

Initial data for newly created instances of this widget.

### `getPreview(array $data): string`

Short text shown on the builder canvas. The default implementation shows the first 60 characters of `$data['content']`, falls back to `$data['label']`, then `$data['src']`.

### `getIcon(): string`

Heroicon name for the widget picker. Default: `heroicon-o-puzzle-piece`.

### `getCategory(): string`

Category for grouping in the picker. Use one of: `content`, `media`, `interactive`, `layout`, `advanced`.

### `getSearchTerms(): array`

Additional keywords for the widget picker search.

### `getValidationRules(): array`

Laravel validation rules for widget data:

```php
public static function getValidationRules(): array
{
    return [
        'title' => 'required|string|max:255',
        'button_url' => 'nullable|url',
    ];
}
```

### `prepareForRender(array $data): array`

Transform stored data before it reaches the Blade view. Use for computed values, URL resolution, or data formatting.

### `getAssets(): array`

Declare JS/CSS dependencies:

```php
public static function getAssets(): array
{
    return [
        'js' => ['https://cdn.example.com/lib.js'],
        'css' => ['https://cdn.example.com/lib.css'],
    ];
}
```

## Lifecycle hooks

Override these for side effects during the widget lifecycle:

```php
// Called when widget is first added to a column
public static function onCreate(array $data, ?WidgetContext $context = null): array
{
    return $data;
}

// Called when the editor slideover is saved
public static function onSave(array $data, ?WidgetContext $context = null): array
{
    return $data;
}

// Called when the widget is deleted
public static function onDelete(array $data, ?WidgetContext $context = null): void
{
    // Clean up uploaded files, etc.
}

// Called when the widget is duplicated
public static function onDuplicate(array $data, ?WidgetContext $context = null): array
{
    // Copy files so the duplicate has its own copy
    return $data;
}
```

The `WidgetContext` object provides:

```php
$context->page;      // ?Page model
$context->rowId;     // ?string
$context->columnId;  // ?string
$context->widgetId;  // ?string
```

## The Blade view

The view receives `$data` (the widget's data array) and `$children` (child components, if any):

```blade
@props(['data', 'children'])

<div>
    <h2>{{ $data['title'] ?? '' }}</h2>
    {!! $data['body'] ?? '' !!}

    @if(!empty($data['button_url']))
        <a href="{{ $data['button_url'] }}">Learn More</a>
    @endif
</div>
```

View naming convention: `resources/views/components/layup/{type}.blade.php` where `{type}` matches `getType()`.

## Registration

Widgets in the `App\Layup\Widgets` namespace are auto-discovered. No config changes needed.

To use a different namespace, update `config/layup.php`:

```php
'widget_discovery' => [
    'namespace' => 'App\\Custom\\Widgets',
    'directory' => app_path('Custom/Widgets'),
],
```

Or register explicitly in the `widgets` config array:

```php
'widgets' => [
    // ... built-in widgets
    \App\Layup\Widgets\BannerWidget::class,
],
```

Or via the plugin:

```php
LayupPlugin::make()
    ->widgets([
        \App\Layup\Widgets\BannerWidget::class,
    ]),
```
