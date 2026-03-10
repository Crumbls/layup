<?php

declare(strict_types=1);

use Crumbls\Layup\Support\WidgetRegistry;
use Illuminate\Support\Facades\Blade;

beforeEach(function (): void {
    $registry = app(WidgetRegistry::class);
    $widgets = config('layup.widgets', []);
    foreach ($widgets as $class) {
        if (class_exists($class) && ! $registry->has($class::getType())) {
            $registry->register($class);
        }
    }
});

it('layup directive is registered', function (): void {
    $directives = Blade::getCustomDirectives();
    expect($directives)->toHaveKey('layup');
});

it('layup directive renders content from array', function (): void {
    $data = [
        'rows' => [
            [
                'id' => 'r1',
                'settings' => [],
                'columns' => [
                    [
                        'id' => 'c1',
                        'span' => 12,
                        'settings' => [],
                        'widgets' => [
                            ['type' => 'text', 'data' => ['content' => 'Blade directive test']],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $html = Blade::render('@layup($data)', ['data' => $data]);
    expect($html)->toContain('Blade directive test');
});

it('layup directive handles empty content', function (): void {
    $html = Blade::render('@layup($data)', ['data' => ['rows' => []]]);
    expect($html)->toBe('');
});

it('layup directive handles null content', function (): void {
    $html = Blade::render('@layup($data)', ['data' => null]);
    expect($html)->toBe('');
});

it('layup directive handles JSON string content', function (): void {
    $json = json_encode([
        'rows' => [
            [
                'id' => 'r1',
                'settings' => [],
                'columns' => [
                    [
                        'id' => 'c1',
                        'span' => 12,
                        'settings' => [],
                        'widgets' => [
                            ['type' => 'text', 'data' => ['content' => 'From JSON directive']],
                        ],
                    ],
                ],
            ],
        ],
    ]);

    $html = Blade::render('@layup($data)', ['data' => $json]);
    expect($html)->toContain('From JSON directive');
});

it('layupScripts directive is registered', function (): void {
    $directives = Blade::getCustomDirectives();
    expect($directives)->toHaveKey('layupScripts');
});
