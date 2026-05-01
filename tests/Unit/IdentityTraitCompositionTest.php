<?php

declare(strict_types=1);

use Crumbls\Layup\View\BaseLivewireWidget;
use Crumbls\Layup\View\BaseWidget;
use Crumbls\Layup\View\ButtonWidget;
use Crumbls\Layup\View\Concerns\Identity\ButtonIdentity;
use Crumbls\Layup\View\Concerns\Identity\HeadingIdentity;
use Crumbls\Layup\View\Concerns\Identity\NewsletterIdentity;
use Crumbls\Layup\View\Concerns\Identity\NumberCounterIdentity;
use Crumbls\Layup\View\HeadingWidget;
use Crumbls\Layup\View\NewsletterWidget;
use Crumbls\Layup\View\NumberCounterWidget;

/**
 * Sister Livewire-flavoured fixtures using the same identity traits as the
 * shipped Blade widgets. The whole point of identity traits is that the
 * editor surface is byte-for-byte identical regardless of render path -
 * so this file pins that claim by comparing every static metadata method
 * between the Blade and Livewire flavours of each refactored widget.
 *
 * If someone later edits HeadingIdentity (or any other) and accidentally
 * inlines render-path-specific logic, these tests will fail.
 */
class HeadingLivewireFixture extends BaseLivewireWidget
{
    use HeadingIdentity;

    public static function getLivewireComponent(): string
    {
        return 'fixture-heading';
    }
}

class ButtonLivewireFixture extends BaseLivewireWidget
{
    use ButtonIdentity;

    public static function getLivewireComponent(): string
    {
        return 'fixture-button';
    }
}

class NumberCounterLivewireFixture extends BaseLivewireWidget
{
    use NumberCounterIdentity;

    public static function getLivewireComponent(): string
    {
        return 'fixture-counter';
    }
}

class NewsletterLivewireFixture extends BaseLivewireWidget
{
    use NewsletterIdentity;

    public static function getLivewireComponent(): string
    {
        return 'fixture-newsletter';
    }
}

dataset('identity_pairs', [
    'heading' => [HeadingWidget::class, HeadingLivewireFixture::class],
    'button' => [ButtonWidget::class, ButtonLivewireFixture::class],
    'number-counter' => [NumberCounterWidget::class, NumberCounterLivewireFixture::class],
    'newsletter' => [NewsletterWidget::class, NewsletterLivewireFixture::class],
]);

it('produces identical type, label, icon and category across both bases', function (string $bladeClass, string $livewireClass): void {
    expect($bladeClass::getType())->toBe($livewireClass::getType());
    expect($bladeClass::getLabel())->toBe($livewireClass::getLabel());
    expect($bladeClass::getIcon())->toBe($livewireClass::getIcon());
    expect($bladeClass::getCategory())->toBe($livewireClass::getCategory());
})->with('identity_pairs');

it('produces identical defaults across both bases', function (string $bladeClass, string $livewireClass): void {
    expect($bladeClass::getDefaultData())->toBe($livewireClass::getDefaultData());
})->with('identity_pairs');

it('produces identical preview output across both bases', function (string $bladeClass, string $livewireClass): void {
    $defaults = $bladeClass::getDefaultData();
    expect($bladeClass::getPreview($defaults))->toBe($livewireClass::getPreview($defaults));
})->with('identity_pairs');

it('produces identical content form schemas (same field names, same defaults)', function (string $bladeClass, string $livewireClass): void {
    $bladeFields = collect($bladeClass::getContentFormSchema())
        ->filter(fn ($c) => method_exists($c, 'getName'))
        ->map(fn ($c) => $c->getName())
        ->all();

    $livewireFields = collect($livewireClass::getContentFormSchema())
        ->filter(fn ($c) => method_exists($c, 'getName'))
        ->map(fn ($c) => $c->getName())
        ->all();

    expect($livewireFields)->toBe($bladeFields);
})->with('identity_pairs');

it('produces identical toArray() metadata across both bases', function (string $bladeClass, string $livewireClass): void {
    expect($bladeClass::toArray())->toBe($livewireClass::toArray());
})->with('identity_pairs');

it('keeps both flavours discoverable through the Widget interface', function (string $bladeClass, string $livewireClass): void {
    $bladeRef = new ReflectionClass($bladeClass);
    $livewireRef = new ReflectionClass($livewireClass);

    expect($bladeRef->implementsInterface(\Crumbls\Layup\Contracts\Widget::class))->toBeTrue();
    expect($livewireRef->implementsInterface(\Crumbls\Layup\Contracts\Widget::class))->toBeTrue();

    // Sibling bases - neither is a subclass of the other
    expect(is_subclass_of($livewireClass, BaseWidget::class))->toBeFalse();
    expect(is_subclass_of($bladeClass, BaseLivewireWidget::class))->toBeFalse();
})->with('identity_pairs');
