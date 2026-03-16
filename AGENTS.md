# Layup Widget Development Guide for AI Agents

This document is a complete reference for AI coding agents (Claude Code, Cursor, Copilot, etc.) to create Layup widgets without error. Follow every rule precisely. Deviations cause runtime failures, missing form fields, or broken rendering.

## Architecture Overview

A Layup widget consists of exactly three files:

1. **PHP class** -- extends `Crumbls\Layup\View\BaseWidget`, defines form fields, defaults, preview, and metadata
2. **Blade view** -- renders the widget's frontend HTML using the `$data` array
3. **Test file** (optional) -- Pest test using `LayupAssertions` trait

The PHP class is a static configuration object. All public methods are `static`. The class is never instantiated by the developer -- `BaseWidget::make($data)` handles construction internally. The Blade view receives `$data` (associative array) and `$children` (array of child view components, usually empty for widgets).

## File Locations

| File | Path | Convention |
|------|------|------------|
| PHP class | `app/Layup/Widgets/{ClassName}Widget.php` | PascalCase, must end in `Widget` |
| Blade view | `resources/views/components/layup/{type}.blade.php` | kebab-case, matches `getType()` |
| Test file | `tests/Unit/Layup/{ClassName}WidgetTest.php` | Matches class name |

For package development (inside crumbls/layup itself):

| File | Path |
|------|------|
| PHP class | `src/View/{ClassName}Widget.php` |
| Blade view | `resources/views/components/{type}.blade.php` |
| Test file | `tests/Unit/{ClassName}WidgetTest.php` |

## The Critical Rule: Field Name Alignment

**Every key in `getDefaultData()` must exactly match a field name in `getContentFormSchema()`, and vice versa.** This is the most common source of errors. The builder stores data using the field names from the form schema. The Blade view accesses data using the keys from `getDefaultData()`. If these do not align, fields silently lose their values or Blade views throw `Undefined array key` errors.

```php
// CORRECT -- field names match default data keys
public static function getContentFormSchema(): array
{
    return [
        TextInput::make('title'),     // field name: 'title'
        TextInput::make('subtitle'),  // field name: 'subtitle'
    ];
}

public static function getDefaultData(): array
{
    return [
        'title' => '',       // matches 'title'
        'subtitle' => '',    // matches 'subtitle'
    ];
}

// WRONG -- mismatch causes silent data loss
public static function getContentFormSchema(): array
{
    return [
        TextInput::make('heading'),  // field name: 'heading'
    ];
}

public static function getDefaultData(): array
{
    return [
        'title' => '',  // WRONG -- does not match 'heading'
    ];
}
```

For `Repeater` fields, the default must be an array of associative arrays matching the repeater's child schema:

```php
Repeater::make('items')
    ->schema([
        TextInput::make('title'),
        RichEditor::make('content'),
    ])

// Default:
'items' => [
    ['title' => 'Item 1', 'content' => ''],
    ['title' => 'Item 2', 'content' => ''],
]
```

## PHP Class Template

Every widget class follows this exact structure. Do not add constructors, instance properties, or non-static methods other than `render()`.

```php
<?php

declare(strict_types=1);

namespace App\Layup\Widgets;

use Crumbls\Layup\View\BaseWidget;
use Crumbls\Layup\Support\WidgetContext;
use Filament\Forms\Components\TextInput;

class ExampleWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'example';
    }

    public static function getLabel(): string
    {
        return 'Example Widget';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-cube';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->required(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'title' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        return $data['title'] ?? '(empty)';
    }
}
```

## Required Static Methods

### getType(): string

Returns a unique kebab-case identifier. This value:
- Is stored in the JSON content structure
- Maps to the Blade view path: `components.layup.{type}` (for app widgets) or `layup::components.{type}` (for package widgets)
- Must be unique across all registered widgets

```php
public static function getType(): string
{
    return 'pricing-card';
}
```

### getLabel(): string

Human-readable name shown in the widget picker. For built-in widgets, use translation keys. For app widgets, a plain string is fine.

```php
// Built-in widget:
public static function getLabel(): string
{
    return __('layup::widgets.labels.pricing-card');
}

// App widget:
public static function getLabel(): string
{
    return 'Pricing Card';
}
```

### getIcon(): string

Must start with `heroicon-o-` (outline) or `heroicon-s-` (solid). The icon appears in the widget picker. Choose an icon that represents the widget's purpose.

Common choices:
- `heroicon-o-document-text` -- text/content widgets
- `heroicon-o-photo` -- image/media widgets
- `heroicon-o-cursor-arrow-rays` -- interactive/button widgets
- `heroicon-o-cube` -- generic custom widgets
- `heroicon-o-chart-bar` -- data/stats widgets
- `heroicon-o-clock` -- time-related widgets
- `heroicon-o-map-pin` -- location widgets

### getCategory(): string

One of these five values. Do not invent new categories:
- `'content'` -- text, headings, lists, accordions, tabs
- `'media'` -- images, video, audio, galleries, maps
- `'interactive'` -- buttons, forms, countdowns, pricing
- `'layout'` -- spacers, dividers, sections
- `'advanced'` -- HTML, code, embeds

### getContentFormSchema(): array

Returns Filament form components for the Content tab only. The Design tab (colors, spacing, borders, shadows) and Advanced tab (ID, classes, CSS, visibility, animations) are inherited automatically from `BaseView`.

**Do not** include design or advanced fields here -- they are handled by the framework.

Available Filament components:
- `TextInput::make('name')` -- single-line text, URL, color, number
- `Textarea::make('name')` -- multi-line text
- `RichEditor::make('name')` -- WYSIWYG HTML editor
- `Select::make('name')` -- dropdown
- `Toggle::make('name')` -- boolean switch
- `Checkbox::make('name')` -- boolean checkbox
- `CheckboxList::make('name')` -- multiple checkboxes
- `FileUpload::make('name')` -- file/image upload
- `Repeater::make('name')` -- repeatable groups
- `DateTimePicker::make('name')` -- date/time

For color fields, use `TextInput` with `->type('color')`:
```php
TextInput::make('bg_color')
    ->label('Background Color')
    ->type('color')
    ->nullable(),
```

For image uploads:
```php
FileUpload::make('src')
    ->label('Image')
    ->image()
    ->directory('layup/images'),
```

The upload disk is applied automatically by `BaseView::withUploadDisk()`. Do not call `->disk()` manually.

### getDefaultData(): array

Every key must match a field name from `getContentFormSchema()`. Provide sensible defaults:
- Strings: `''` (empty string)
- Booleans: `false` or `true`
- Numbers: `0`
- Selects: the default option value (e.g., `'primary'`)
- Repeaters: array with 1-3 example items
- File uploads: `''`

### getPreview(array $data): string

Returns a short string shown on the builder canvas. The base class provides a fallback that checks `$data['content']`, then `$data['label']`, then `$data['src']`. Override for better previews.

Pattern: descriptive text showing the widget's key value.

```php
// Simple:
return $data['title'] ?? '(empty)';

// With context:
$count = count($data['items'] ?? []);
return "Accordion -- {$count} items";

// With URL:
$label = $data['label'] ?? 'Button';
$url = $data['url'] ?? '#';
return "{$label}" . ($url !== '#' ? " -> {$url}" : '');
```

## Blade View Template

Every Blade view must integrate four features from the Design and Advanced tabs. These are provided as static helper methods on `BaseView`. Missing any of these means that feature silently stops working for your widget.

### Mandatory Integration Points

1. **`id` attribute** -- from Advanced tab
2. **`class` attribute** -- visibility classes + user's custom classes
3. **`style` attribute** -- inline styles from Design tab
4. **Animation attributes** -- Alpine.js directives from Advanced tab

### Standard Wrapper Pattern

```blade
@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
@endphp
<div
    @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
    class="{{ $vis }} {{ $data['class'] ?? '' }}"
    style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
    {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    {{-- Widget content here --}}
</div>
```

### Helper Methods Reference

**`BaseView::visibilityClasses(array $hideOn): string`**

Converts the `hide_on` checkbox values into Tailwind responsive visibility classes. Always pass `$data['hide_on'] ?? []`.

```php
// Input: ['sm', 'lg']
// Output: 'hidden md:block lg:hidden'
```

**`BaseView::buildInlineStyles(array $data): string`**

Reads Design tab fields and compiles them into a CSS string. Pass the entire `$data` array. Handles: `text_color`, `text_align`, `font_size`, `border_radius`, `border_width`+`border_style`+`border_color`, `box_shadow`, `opacity`, `background_color`, `inline_css`.

```php
// Output: "color: #333; text-align: center; font-size: 1.25rem; background-color: #f0f0f0;"
```

**`BaseView::animationAttributes(array $data): string`**

Generates Alpine.js directives for entrance animations. Returns an empty string if no animation is configured. Use `{!! !!}` (unescaped) because the output contains HTML attributes.

```php
// Output: 'x-data="{ shown: false }" x-intersect.once="shown = true" :style="shown ? ..."'
```

### Data Access in Blade

Always use null-coalescing for every data access:

```blade
{{-- Text output (escaped) --}}
{{ $data['title'] ?? '' }}

{{-- HTML output (unescaped -- for RichEditor content) --}}
{!! $data['content'] ?? '' !!}

{{-- Conditional rendering --}}
@if(!empty($data['caption']))
    <p>{{ $data['caption'] }}</p>
@endif

{{-- File uploads -- check is_array for edit-mode compatibility --}}
@if(!empty($data['src']))
    <img src="{{ is_array($data['src']) ? '' : asset('storage/' . $data['src']) }}"
         alt="{{ $data['alt'] ?? '' }}" />
@endif

{{-- Repeater items --}}
@foreach(($data['items'] ?? []) as $index => $item)
    <div>{{ $item['title'] ?? '' }}</div>
@endforeach

{{-- Select/match for variant styles --}}
{{ match($data['style'] ?? 'primary') {
    'primary' => 'bg-blue-600 text-white',
    'secondary' => 'bg-gray-600 text-white',
    default => 'bg-blue-600 text-white',
} }}
```

### File Upload Image Handling

File uploads store a string path during save (e.g., `layup/images/photo.jpg`) but may be a temporary array during editing. Always guard:

```blade
@php
    $src = is_array($data['src'] ?? null) ? '' : ($data['src'] ?? '');
@endphp
@if($src !== '')
    <img src="{{ asset('storage/' . $src) }}" alt="{{ $data['alt'] ?? '' }}" />
@endif
```

### Using WidgetData (Optional)

For cleaner Blade views, use the `WidgetData` value object:

```blade
@php $d = \Crumbls\Layup\Support\WidgetData::from($data); @endphp
<h1>{{ $d->string('title') }}</h1>
<img src="{{ $d->storageUrl('src') }}" alt="{{ $d->string('alt') }}" />
@if($d->bool('show_overlay'))
    <div style="opacity: {{ $d->float('opacity', 0.5) }}"></div>
@endif
```

## Optional Static Methods

These have no-op defaults in `BaseWidget`. Override only when needed.

### prepareForRender(array $data): array

Transform data before it reaches the Blade view. Use for computed values, type casting, or URL resolution. Called automatically in the render pipeline.

```php
public static function prepareForRender(array $data): array
{
    $data['target_timestamp'] = strtotime($data['target_date'] ?? 'now');
    $data['is_expired'] = $data['target_timestamp'] < time();

    return $data;
}
```

### getValidationRules(): array

Validation rules checked by `ContentValidator`. Keys are field names, values are Laravel validation rule strings.

```php
public static function getValidationRules(): array
{
    return [
        'label' => 'required|string',
        'url' => 'required|url',
    ];
}
```

### getSearchTerms(): array

Extra terms for the widget picker search. The picker already searches `getType()` and `getLabel()`.

```php
public static function getSearchTerms(): array
{
    return ['cta', 'action', 'conversion'];
}
```

### isDeprecated(): bool / getDeprecationMessage(): string

Mark a widget for removal. Surfaced in `layup:doctor` and `layup:audit`.

```php
public static function isDeprecated(): bool
{
    return true;
}

public static function getDeprecationMessage(): string
{
    return 'Use CardWidget instead. Removal planned for v2.0.';
}
```

### getAssets(): array

Declare external JS/CSS the widget requires. Collected by `WidgetAssetCollector`.

```php
public static function getAssets(): array
{
    return [
        'js' => ['https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js'],
        'css' => [],
    ];
}
```

### Lifecycle Hooks

All receive `$data` and optional `WidgetContext`. All return the (possibly modified) `$data` array except `onDelete` which returns `void`.

```php
// Called after the form is saved
public static function onSave(array $data, ?WidgetContext $context = null): array
{
    // Normalize, sanitize, or transform data before storage
    return $data;
}

// Called when widget is first created
public static function onCreate(array $data, ?WidgetContext $context = null): array
{
    return $data;
}

// Called when widget is deleted -- cleanup resources
public static function onDelete(array $data, ?WidgetContext $context = null): void
{
    if (!empty($data['src']) && is_string($data['src'])) {
        Storage::disk(config('layup.uploads.disk', 'public'))->delete($data['src']);
    }
}

// Called when widget is duplicated -- clone resources
public static function onDuplicate(array $data, ?WidgetContext $context = null): array
{
    if (!empty($data['src']) && is_string($data['src'])) {
        $ext = pathinfo($data['src'], PATHINFO_EXTENSION);
        $newPath = 'layup/images/' . Str::uuid() . '.' . $ext;
        Storage::disk(config('layup.uploads.disk', 'public'))->copy($data['src'], $newPath);
        $data['src'] = $newPath;
    }

    return $data;
}
```

## Form Field Packs

Reusable field groups for common patterns. Spread them into `getContentFormSchema()`:

```php
use Crumbls\Layup\Support\FieldPacks;

public static function getContentFormSchema(): array
{
    return [
        TextInput::make('heading')->required(),
        ...FieldPacks::image('hero'),         // hero_src (FileUpload) + hero_alt (TextInput)
        ...FieldPacks::link('cta'),           // cta_url (TextInput) + cta_new_tab (Toggle)
        ...FieldPacks::colorPair('text', 'bg'), // text_color + bg_color
        ...FieldPacks::hoverColors('btn'),    // btn_bg_color, btn_hover_bg_color, btn_text_color, btn_hover_text_color
    ];
}
```

When using FieldPacks, the field names are prefixed. Your `getDefaultData()` must match:

```php
public static function getDefaultData(): array
{
    return [
        'heading' => '',
        'hero_src' => '',        // from FieldPacks::image('hero')
        'hero_alt' => '',        // from FieldPacks::image('hero')
        'cta_url' => '',         // from FieldPacks::link('cta')
        'cta_new_tab' => false,  // from FieldPacks::link('cta')
        'text_color' => '',      // from FieldPacks::colorPair('text', 'bg')
        'bg_color' => '',        // from FieldPacks::colorPair('text', 'bg')
        'btn_bg_color' => '',    // from FieldPacks::hoverColors('btn')
        'btn_hover_bg_color' => '',
        'btn_text_color' => '',
        'btn_hover_text_color' => '',
    ];
}
```

## Registration

After creating the PHP class and Blade view, the widget must be registered.

**Option 1: Auto-discovery** -- place in `app/Layup/Widgets/`. Also register with the Filament plugin:
```php
LayupPlugin::make()->widgets([MyWidget::class])
```

**Option 2: Config** -- add to `config/layup.php` `widgets` array.

**Option 3: Plugin only** -- register via `LayupPlugin::make()->widgets([...])`.

## Test File

Generate with `php artisan layup:make-widget MyWidget --with-test`, or create manually:

```php
<?php

use Crumbls\Layup\Testing\LayupAssertions;
use App\Layup\Widgets\MyWidget;

uses(LayupAssertions::class);

it('satisfies the widget contract', function () {
    $this->assertWidgetContractValid(MyWidget::class);
});

it('defaults cover all form fields', function () {
    $this->assertDefaultsCoverFormFields(MyWidget::class);
});

it('renders with default data', function () {
    $this->assertWidgetRendersWithDefaults(MyWidget::class);
});
```

The assertions check:
- `assertWidgetContractValid` -- implements Widget, type/label/icon/category non-empty, icon starts with `heroicon-`, form schema is array, defaults is array, preview returns string, `toArray()` has required keys
- `assertDefaultsCoverFormFields` -- every field name in `getContentFormSchema()` has a corresponding key in `getDefaultData()`
- `assertWidgetRendersWithDefaults` -- widget renders non-empty HTML with default data

## Debugging

Use the Artisan command to inspect a widget's full state:

```bash
php artisan layup:debug-widget my-type --data='{"title":"Hello"}'
```

This prints: type, class, category, deprecated status, form fields, defaults, merged data, validation rules, assets, search terms, preview, prepareForRender output, and rendered HTML.

## Complete Example: Testimonial Card Widget

### PHP Class

```php
<?php

declare(strict_types=1);

namespace App\Layup\Widgets;

use Crumbls\Layup\View\BaseWidget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class TestimonialCardWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'testimonial-card';
    }

    public static function getLabel(): string
    {
        return 'Testimonial Card';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-chat-bubble-bottom-center-text';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            Textarea::make('quote')
                ->label('Quote')
                ->required()
                ->rows(3),
            TextInput::make('author')
                ->label('Author Name')
                ->required(),
            TextInput::make('role')
                ->label('Role / Title'),
            FileUpload::make('avatar')
                ->label('Avatar')
                ->image()
                ->directory('layup/images')
                ->avatar(),
            Select::make('style')
                ->label('Card Style')
                ->options([
                    'default' => 'Default',
                    'bordered' => 'Bordered',
                    'filled' => 'Filled Background',
                ])
                ->default('default'),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'quote' => '',
            'author' => '',
            'role' => '',
            'avatar' => '',
            'style' => 'default',
        ];
    }

    public static function getPreview(array $data): string
    {
        $author = $data['author'] ?? '';
        $quote = $data['quote'] ?? '';
        $short = mb_strlen($quote) > 40 ? mb_substr($quote, 0, 40) . '...' : $quote;

        if ($author !== '') {
            return "\"{$short}\" -- {$author}";
        }

        return $short !== '' ? "\"{$short}\"" : '(empty testimonial)';
    }

    public static function getSearchTerms(): array
    {
        return ['review', 'feedback', 'quote', 'endorsement'];
    }

    public static function getValidationRules(): array
    {
        return [
            'quote' => 'required|string',
            'author' => 'required|string',
        ];
    }
}
```

### Blade View

File: `resources/views/components/layup/testimonial-card.blade.php`

```blade
@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $styles = \Crumbls\Layup\View\BaseView::buildInlineStyles($data);
    $avatar = is_array($data['avatar'] ?? null) ? '' : ($data['avatar'] ?? '');
    $cardClass = match($data['style'] ?? 'default') {
        'bordered' => 'border border-gray-200 dark:border-gray-700',
        'filled' => 'bg-gray-50 dark:bg-gray-800',
        default => '',
    };
@endphp
<blockquote
    @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
    class="rounded-lg p-6 {{ $cardClass }} {{ $vis }} {{ $data['class'] ?? '' }}"
    style="{{ $styles }}"
    {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @if(!empty($data['quote']))
        <p class="text-gray-700 dark:text-gray-300 italic mb-4">"{{ $data['quote'] }}"</p>
    @endif
    <footer class="flex items-center gap-3">
        @if($avatar !== '')
            <img src="{{ asset('storage/' . $avatar) }}" alt="{{ $data['author'] ?? '' }}" class="w-10 h-10 rounded-full object-cover" />
        @endif
        <div>
            @if(!empty($data['author']))
                <cite class="font-medium text-gray-900 dark:text-white not-italic">{{ $data['author'] }}</cite>
            @endif
            @if(!empty($data['role']))
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $data['role'] }}</p>
            @endif
        </div>
    </footer>
</blockquote>
```

### Test File

File: `tests/Unit/Layup/TestimonialCardWidgetTest.php`

```php
<?php

use Crumbls\Layup\Testing\LayupAssertions;
use App\Layup\Widgets\TestimonialCardWidget;

uses(LayupAssertions::class);

it('satisfies the widget contract', function () {
    $this->assertWidgetContractValid(TestimonialCardWidget::class);
});

it('defaults cover all form fields', function () {
    $this->assertDefaultsCoverFormFields(TestimonialCardWidget::class);
});

it('renders with default data', function () {
    $this->assertWidgetRendersWithDefaults(TestimonialCardWidget::class);
});
```

## Checklist for Every Widget

Before considering a widget complete, verify each item:

- [ ] `declare(strict_types=1)` at the top of the PHP file
- [ ] Class extends `BaseWidget`
- [ ] `getType()` returns kebab-case string
- [ ] `getType()` value matches the Blade view filename (minus `.blade.php`)
- [ ] `getLabel()` returns non-empty string
- [ ] `getIcon()` returns a string starting with `heroicon-`
- [ ] `getCategory()` returns one of: `content`, `media`, `interactive`, `layout`, `advanced`
- [ ] `getContentFormSchema()` returns array of Filament components
- [ ] `getDefaultData()` has a key for every field in `getContentFormSchema()`
- [ ] `getDefaultData()` does not have keys that are not in `getContentFormSchema()`
- [ ] `getPreview()` returns a non-empty string for default data
- [ ] Blade view uses `$data['key'] ?? ''` (null coalescing) for every data access
- [ ] Blade view includes `id` attribute: `@if(!empty($data['id']))id="{{ $data['id'] }}"@endif`
- [ ] Blade view includes visibility: `{{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }}`
- [ ] Blade view includes custom classes: `{{ $data['class'] ?? '' }}`
- [ ] Blade view includes inline styles: `style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"`
- [ ] Blade view includes animations: `{!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}`
- [ ] File uploads use `is_array()` guard in Blade
- [ ] RichEditor content uses `{!! !!}` (unescaped output)
- [ ] Plain text uses `{{ }}` (escaped output)
- [ ] No design/advanced fields in `getContentFormSchema()` (inherited from BaseView)
- [ ] Widget registered via auto-discovery, config, or plugin

## Common Mistakes

1. **Missing default for a form field** -- causes `Undefined array key` in Blade. Run `layup:doctor` to detect.
2. **Field name in schema does not match key in defaults** -- data entered in the form is lost on save.
3. **Using `$data['key']` without `?? ''`** -- throws errors when data is incomplete.
4. **Forgetting `is_array()` check on FileUpload data** -- breaks during editing when Livewire sends temp array.
5. **Adding Design tab fields to `getContentFormSchema()`** -- duplicates fields, confuses users.
6. **Using `{{ }}` for RichEditor content** -- escapes HTML, renders raw tags as text.
7. **Omitting animation attributes** -- entrance animations stop working for the widget.
8. **Omitting visibility classes** -- responsive hide/show stops working.
9. **Using a `getType()` that does not match the view filename** -- widget renders blank.
10. **Forgetting to register the widget** -- widget type is unknown, pages using it render nothing.
