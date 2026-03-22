<?php

declare(strict_types=1);

use Crumbls\Layup\Support\LayupContent;
use Crumbls\Layup\Support\WidgetRegistry;

beforeEach(function (): void {
    $registry = app(WidgetRegistry::class);
    foreach (config('layup.widgets', []) as $class) {
        if (class_exists($class) && ! $registry->has($class::getType())) {
            $registry->register($class);
        }
    }
});

it('serializes content tree to array', function (): void {
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
                            ['type' => 'text', 'data' => ['content' => 'Serialize me']],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $content = new LayupContent($data);
    $array = $content->toArray();

    expect($array)->toBeArray();
    expect($array[0]['type'])->toBe('row');
    expect($array[0]['children'])->toBeArray();
    expect($array[0]['children'][0]['type'])->toBe('column');
    expect($array[0]['children'][0]['children'][0]['type'])->toBe('text');
    expect($array[0]['children'][0]['children'][0]['data']['content'])->toBe('Serialize me');
});

it('returns empty array for empty content', function (): void {
    $content = new LayupContent(['rows' => []]);
    expect($content->toArray())->toBe([]);
});

it('includes column spans', function (): void {
    $data = [
        'rows' => [
            [
                'id' => 'r1',
                'settings' => [],
                'columns' => [
                    [
                        'id' => 'c1',
                        'span' => 6,
                        'settings' => [],
                        'widgets' => [],
                    ],
                    [
                        'id' => 'c2',
                        'span' => 6,
                        'settings' => [],
                        'widgets' => [],
                    ],
                ],
            ],
        ],
    ];

    $content = new LayupContent($data);
    $array = $content->toArray();

    expect($array[0]['children'])->toHaveCount(2);
    expect($array[0]['children'][0]['span'])->toBeArray();
    expect($array[0]['children'][1]['span'])->toBeArray();
});

it('serializes sections structure', function (): void {
    $data = [
        'sections' => [
            [
                'settings' => ['bg' => 'blue'],
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
                                    ['type' => 'text', 'data' => ['content' => 'In section']],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $content = new LayupContent($data);
    $array = $content->toArray();

    expect($array)->toBeArray();
    expect($array[0]['children'][0]['children'][0]['type'])->toBe('text');
});
