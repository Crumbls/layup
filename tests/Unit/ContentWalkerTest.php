<?php

declare(strict_types=1);

use Crumbls\Layup\Support\ContentWalker;

it('extracts rows from legacy rows structure', function (): void {
    $content = [
        'rows' => [
            ['id' => 'r1', 'columns' => []],
            ['id' => 'r2', 'columns' => []],
        ],
    ];

    $rows = ContentWalker::extractRows($content);

    expect($rows)->toHaveCount(2)
        ->and($rows[0]['id'])->toBe('r1')
        ->and($rows[1]['id'])->toBe('r2');
});

it('extracts rows from sections structure', function (): void {
    $content = [
        'sections' => [
            ['settings' => [], 'rows' => [['id' => 'r1', 'columns' => []]]],
            ['settings' => [], 'rows' => [['id' => 'r2', 'columns' => []], ['id' => 'r3', 'columns' => []]]],
        ],
    ];

    $rows = ContentWalker::extractRows($content);

    expect($rows)->toHaveCount(3)
        ->and($rows[0]['id'])->toBe('r1')
        ->and($rows[2]['id'])->toBe('r3');
});

it('prefers sections over rows when both present', function (): void {
    $content = [
        'sections' => [
            ['settings' => [], 'rows' => [['id' => 'from-section', 'columns' => []]]],
        ],
        'rows' => [
            ['id' => 'from-rows', 'columns' => []],
        ],
    ];

    $rows = ContentWalker::extractRows($content);

    expect($rows)->toHaveCount(1)
        ->and($rows[0]['id'])->toBe('from-section');
});

it('returns empty array for empty content', function (): void {
    expect(ContentWalker::extractRows([]))->toBe([]);
});

it('walks widgets in legacy rows structure', function (): void {
    $content = [
        'rows' => [[
            'id' => 'r1',
            'columns' => [[
                'id' => 'c1',
                'widgets' => [
                    ['type' => 'text', 'data' => ['content' => 'hello']],
                    ['type' => 'image', 'data' => ['src' => 'img.jpg']],
                ],
            ]],
        ]],
    ];

    $found = [];
    ContentWalker::walkWidgets($content, function (string $type, array $data) use (&$found): void {
        $found[] = $type;
    });

    expect($found)->toBe(['text', 'image']);
});

it('walks widgets in sections structure', function (): void {
    $content = [
        'sections' => [[
            'settings' => [],
            'rows' => [[
                'id' => 'r1',
                'columns' => [[
                    'id' => 'c1',
                    'widgets' => [
                        ['type' => 'hero', 'data' => []],
                    ],
                ]],
            ]],
        ]],
    ];

    $found = [];
    ContentWalker::walkWidgets($content, function (string $type) use (&$found): void {
        $found[] = $type;
    });

    expect($found)->toBe(['hero']);
});

it('collects widget types correctly', function (): void {
    $content = [
        'rows' => [[
            'id' => 'r1',
            'columns' => [[
                'id' => 'c1',
                'widgets' => [
                    ['type' => 'text', 'data' => []],
                    ['type' => 'text', 'data' => []],
                    ['type' => 'image', 'data' => []],
                ],
            ]],
        ]],
    ];

    $types = ContentWalker::collectWidgetTypes($content);

    expect($types)->toBe(['text' => 2, 'image' => 1]);
});
