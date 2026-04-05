---
title: Extending Existing Widgets
weight: 2
---

## Extending a built-in widget

To modify an existing widget's behavior, extend its class:

```php
<?php

declare(strict_types=1);

namespace App\Layup\Widgets;

use Crumbls\Layup\View\HeadingWidget;
use Filament\Forms\Components\Select;

class CustomHeadingWidget extends HeadingWidget
{
    public static function getType(): string
    {
        return 'custom-heading';
    }

    public static function getLabel(): string
    {
        return 'Custom Heading';
    }

    public static function getContentFormSchema(): array
    {
        return array_merge(parent::getContentFormSchema(), [
            Select::make('style')
                ->label('Style')
                ->options([
                    'default' => 'Default',
                    'underline' => 'With Underline',
                    'accent' => 'Accent Color',
                ]),
        ]);
    }
}
```

## Replacing a built-in widget

To completely replace a built-in widget, use `withoutWidgets()` on the plugin and register your replacement:

```php
use Crumbls\Layup\LayupPlugin;
use Crumbls\Layup\View\HeadingWidget;
use App\Layup\Widgets\CustomHeadingWidget;

LayupPlugin::make()
    ->withoutWidgets([HeadingWidget::class])
    ->widgets([CustomHeadingWidget::class]),
```

## Removing widgets

Remove widgets you do not need:

```php
use Crumbls\Layup\View\LoginWidget;
use Crumbls\Layup\View\CookieConsentWidget;

LayupPlugin::make()
    ->withoutWidgets([
        LoginWidget::class,
        CookieConsentWidget::class,
    ]),
```

## Using only specific widgets

To start with no built-in widgets and register only the ones you need:

```php
LayupPlugin::make()
    ->withoutConfigWidgets()
    ->widgets([
        \Crumbls\Layup\View\TextWidget::class,
        \Crumbls\Layup\View\HeadingWidget::class,
        \Crumbls\Layup\View\ImageWidget::class,
        \Crumbls\Layup\View\ButtonWidget::class,
        \App\Layup\Widgets\BannerWidget::class,
    ]),
```

## Customizing widget views

Publish the Blade views to override rendering for any built-in widget:

```bash
php artisan vendor:publish --tag=layup-views
```

Views are published to `resources/views/vendor/layup/components/`. Edit the specific widget view (e.g., `heading.blade.php`) to change its HTML output.
