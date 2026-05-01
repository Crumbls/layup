<?php

declare(strict_types=1);

use Crumbls\Layup\View\BaseView;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

/**
 * Three fixture views, one returning each of the three legal render()
 * return types (View | Htmlable | string). renderChildrenToHtml() must
 * coerce all three into a single concatenated string. This pins the
 * contract that every render path is interchangeable inside a parent's
 * children list -- the load-bearing claim that makes mixed
 * Blade+Livewire trees work.
 *
 * The Blade-view fixture lives at tests/views/render-child-fixture.blade.php
 * and is auto-located by the existing tests/views path registered in
 * TestCase::defineEnvironment().
 */
class StringChild extends BaseView
{
    public function render(): string
    {
        return '<span data-marker="string">str</span>';
    }
}

class HtmlableChild extends BaseView
{
    public function render(): Htmlable
    {
        return new HtmlString('<span data-marker="htmlable">htm</span>');
    }
}

class ViewChild extends BaseView
{
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('render-child-fixture', ['marker' => 'view']);
    }
}

it('returns an empty string when there are no children', function (): void {
    $parent = StringChild::make();

    expect($parent->renderChildrenToHtml())->toBe('');
});

it('concatenates string-returning children in order', function (): void {
    $parent = StringChild::make([], [
        StringChild::make(),
        StringChild::make(),
    ]);

    $html = $parent->renderChildrenToHtml();

    expect($html)->toBe(
        '<span data-marker="string">str</span><span data-marker="string">str</span>'
    );
});

it('coerces Htmlable-returning children into the concatenated string', function (): void {
    $parent = StringChild::make([], [
        HtmlableChild::make(),
    ]);

    expect($parent->renderChildrenToHtml())
        ->toBe('<span data-marker="htmlable">htm</span>');
});

it('coerces View-returning children into the concatenated string', function (): void {
    $parent = StringChild::make([], [
        ViewChild::make(),
    ]);

    expect($parent->renderChildrenToHtml())
        ->toContain('data-marker="view"');
});

it('handles a heterogeneous mix of View, Htmlable, and string children', function (): void {
    $parent = StringChild::make([], [
        StringChild::make(),
        HtmlableChild::make(),
        ViewChild::make(),
    ]);

    $html = $parent->renderChildrenToHtml();

    expect($html)->toContain('data-marker="string"');
    expect($html)->toContain('data-marker="htmlable"');
    expect($html)->toContain('data-marker="view"');
});
