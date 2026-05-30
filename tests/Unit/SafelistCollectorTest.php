<?php

declare(strict_types=1);

use Crumbls\Layup\Events\SafelistChanged;
use Crumbls\Layup\Models\Page;
use Crumbls\Layup\Support\SafelistCollector;
use Illuminate\Support\Facades\Event;

it('returns static classes from safelist file', function (): void {
    $classes = SafelistCollector::staticClasses();

    expect($classes)->toBeArray()
        ->and(count($classes))->toBeGreaterThan(40)
        ->and($classes)->toContain('container')
        ->and($classes)->toContain('flex')
        ->and($classes)->toContain('flex-wrap')
        ->and($classes)->toContain('mx-auto')
        ->and($classes)->toContain('md:pr-2')
        ->and($classes)->toContain('md:px-2')
        ->and($classes)->toContain('md:pl-2')
        ->and($classes)->toContain('space-y-4')
        ->and($classes)->toContain('w-full');
});

it('returns empty array from classesFromContent with null content', function (): void {
    expect(SafelistCollector::classesFromContent(null))->toBe([]);
});

it('returns empty array from classesFromContent with empty rows', function (): void {
    expect(SafelistCollector::classesFromContent(['rows' => []]))->toBe([]);
});

it('extracts user custom classes from row settings', function (): void {
    $content = ['rows' => [[
        'id' => 'r1',
        'settings' => ['class' => 'my-custom bg-brand-500'],
        'columns' => [],
    ]]];

    $classes = SafelistCollector::classesFromContent($content);
    expect($classes)->toContain('my-custom')
        ->and($classes)->toContain('bg-brand-500');
});

it('extracts user custom classes from column settings', function (): void {
    $content = ['rows' => [[
        'id' => 'r1',
        'settings' => [],
        'columns' => [[
            'id' => 'c1',
            'settings' => ['class' => 'col-special'],
            'widgets' => [],
        ]],
    ]]];

    $classes = SafelistCollector::classesFromContent($content);
    expect($classes)->toContain('col-special');
});

it('extracts user custom classes from widget data', function (): void {
    $content = ['rows' => [[
        'id' => 'r1',
        'settings' => [],
        'columns' => [[
            'id' => 'c1',
            'settings' => [],
            'widgets' => [[
                'id' => 'w1',
                'type' => 'text',
                'data' => ['class' => 'prose prose-lg'],
            ]],
        ]],
    ]]];

    $classes = SafelistCollector::classesFromContent($content);
    expect($classes)->toContain('prose')
        ->and($classes)->toContain('prose-lg');
});

it('deduplicates classes', function (): void {
    $content = ['rows' => [
        ['id' => 'r1', 'settings' => ['class' => 'dupe'], 'columns' => [[
            'id' => 'c1', 'settings' => ['class' => 'dupe'], 'widgets' => [],
        ]]],
    ]];

    $classes = SafelistCollector::classesFromContent($content);
    expect(array_count_values($classes)['dupe'])->toBe(1);
});

it('extracts gap class from row settings', function (): void {
    $content = ['rows' => [[
        'id' => 'r1',
        'settings' => ['gap' => 'gap-6'],
        'columns' => [],
    ]]];

    $classes = SafelistCollector::classesFromContent($content);
    expect($classes)->toContain('gap-6');
});

it('merges static and dynamic classes', function (): void {
    Page::create([
        'title' => 'Test',
        'slug' => 'safelist-merge',
        'content' => ['rows' => [[
            'id' => 'r1',
            'settings' => ['class' => 'unique-dynamic-class'],
            'columns' => [],
        ]]],
        'status' => 'published',
    ]);

    $all = SafelistCollector::classes();
    expect($all)->toContain('container') // static
        ->and($all)->toContain('unique-dynamic-class'); // dynamic
});

it('generates safelist file string with one class per line', function (): void {
    $output = SafelistCollector::toSafelistFile();
    $lines = array_filter(explode("\n", trim($output)));
    expect(count($lines))->toBeGreaterThan(40);
    // Should be sorted
    $sorted = $lines;
    sort($sorted);
    expect($lines)->toBe($sorted);
});

it('extracts inline styles from content', function (): void {
    $content = ['rows' => [[
        'id' => 'r1',
        'settings' => ['inline_css' => 'background: red;'],
        'columns' => [[
            'id' => 'c1',
            'settings' => ['inline_css' => 'border: 1px solid;'],
            'widgets' => [[
                'id' => 'w1',
                'type' => 'text',
                'data' => ['inline_css' => 'color: blue;'],
            ]],
        ]],
    ]]];

    $styles = SafelistCollector::inlineStylesFromContent($content);
    expect($styles)->toContain('background: red;')
        ->and($styles)->toContain('border: 1px solid;')
        ->and($styles)->toContain('color: blue;');
});

it('sync dispatches SafelistChanged event on change', function (): void {
    Event::fake([SafelistChanged::class]);
    cache()->forget('layup:safelist:hash');
    cache()->forget('layup:safelist:classes');

    $path = sys_get_temp_dir() . '/layup-test-safelist-' . uniqid() . '.txt';

    SafelistCollector::sync($path);

    Event::assertDispatched(SafelistChanged::class);

    @unlink($path);
});

it('sync does not dispatch when hash unchanged', function (): void {
    Event::fake([SafelistChanged::class]);
    cache()->forget('layup:safelist:hash');
    cache()->forget('layup:safelist:classes');

    $path = sys_get_temp_dir() . '/layup-test-safelist-' . uniqid() . '.txt';

    SafelistCollector::sync($path); // first call sets hash
    Event::assertDispatched(SafelistChanged::class);

    Event::fake([SafelistChanged::class]); // reset
    SafelistCollector::sync($path); // second call — same hash
    Event::assertNotDispatched(SafelistChanged::class);

    @unlink($path);
});

it('sync writes file to disk', function (): void {
    cache()->forget('layup:safelist:hash');
    cache()->forget('layup:safelist:classes');

    $path = sys_get_temp_dir() . '/layup-test-safelist-' . uniqid() . '.txt';

    SafelistCollector::sync($path);

    expect(file_exists($path))->toBeTrue();
    $contents = file_get_contents($path);
    expect($contents)->toContain('container');

    @unlink($path);
});

it('classesForPages works with empty collection', function (): void {
    expect(SafelistCollector::classesForPages([]))->toBe([]);
});

it('includes extra_classes from config', function (): void {
    config(['layup.safelist.extra_classes' => ['custom-brand', 'bg-brand-500']]);

    $all = SafelistCollector::classes();
    expect($all)->toContain('custom-brand')
        ->and($all)->toContain('bg-brand-500');

    config(['layup.safelist.extra_classes' => []]);
});

it('extracts classes from sections structure', function (): void {
    $content = [
        'sections' => [[
            'settings' => [],
            'rows' => [[
                'id' => 'r1',
                'settings' => ['class' => 'section-custom-class'],
                'columns' => [],
            ]],
        ]],
    ];

    $classes = SafelistCollector::classesFromContent($content);
    expect($classes)->toContain('section-custom-class');
});

it('extracts inline styles from sections structure', function (): void {
    $content = [
        'sections' => [[
            'settings' => [],
            'rows' => [[
                'id' => 'r1',
                'settings' => ['inline_css' => 'background: green;'],
                'columns' => [],
            ]],
        ]],
    ];

    $styles = SafelistCollector::inlineStylesFromContent($content);
    expect($styles)->toContain('background: green;');
});

it('returns empty array from classesFromContent with sections but empty rows', function (): void {
    $content = ['sections' => [['settings' => [], 'rows' => []]]];
    expect(SafelistCollector::classesFromContent($content))->toBe([]);
});
