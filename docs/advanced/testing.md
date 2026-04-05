---
title: Testing
weight: 8
---

Layup ships with factory states and assertion helpers for testing pages and custom widgets.

## Factory states

```php
use Crumbls\Layup\Models\Page;

// Page with specific widget types
$page = Page::factory()->withWidgets(['text', 'button'])->create();

// Page with explicit content structure
$page = Page::factory()->withContent([...])->create();
```

## Assertions

Add the `LayupAssertions` trait to your test case:

```php
use Crumbls\Layup\Testing\LayupAssertions;

test('homepage has expected widgets', function () {
    $page = Page::factory()->withWidgets(['heading', 'text', 'button'])->create();

    $this->assertPageContainsWidget($page, 'heading');
    $this->assertPageContainsWidget($page, 'button', expectedCount: 1);
    $this->assertPageDoesNotContainWidget($page, 'html');
    $this->assertPageRenders($page);
});

test('custom widget renders without errors', function () {
    $this->assertWidgetRenders('my-widget', ['title' => 'Hello']);
});
```

## Widget contract assertions

Validate that a custom widget follows all conventions:

```php
test('widget satisfies the contract', function () {
    $this->assertWidgetContractValid(MyWidget::class);
});

test('defaults cover all form fields', function () {
    $this->assertDefaultsCoverFormFields(MyWidget::class);
});

test('renders with default data', function () {
    $this->assertWidgetRendersWithDefaults(MyWidget::class);
});
```

Generate these tests automatically:

```bash
php artisan layup:make-widget MyWidget --with-test
```

This creates `tests/Unit/Layup/MyWidgetTest.php` with contract, defaults, and render assertions.
