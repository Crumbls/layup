<?php

declare(strict_types=1);

use Crumbls\Layup\Support\LayupContent;
use Crumbls\Layup\Support\WidgetRegistry;
use Illuminate\Contracts\Support\Htmlable;

beforeEach(function (): void {
    $registry = app(WidgetRegistry::class);
    $widgets = config('layup.widgets', []);
    foreach ($widgets as $class) {
        if (class_exists($class) && ! $registry->has($class::getType())) {
            $registry->register($class);
        }
    }
});

// --- Constructor & normalization ---

it('accepts array content', function (): void {
    $content = new LayupContent(['rows' => []]);
    expect($content->toHtml())->toBe('');
});

it('accepts JSON string content', function (): void {
    $json = json_encode(['rows' => []]);
    $content = new LayupContent($json);
    expect($content->toHtml())->toBe('');
});

it('accepts null gracefully', function (): void {
    $content = new LayupContent(null);
    expect($content->toHtml())->toBe('');
});

it('accepts empty string gracefully', function (): void {
    $content = new LayupContent('');
    expect($content->toHtml())->toBe('');
});

it('accepts malformed JSON gracefully', function (): void {
    $content = new LayupContent('{not valid json');
    expect($content->toHtml())->toBe('');
});

it('accepts integer gracefully', function (): void {
    $content = new LayupContent(42);
    expect($content->toHtml())->toBe('');
});

// --- Htmlable contract ---

it('implements Htmlable', function (): void {
    $content = new LayupContent(['rows' => []]);
    expect($content)->toBeInstanceOf(Htmlable::class);
});

// --- Rendering with flat rows ---

it('renders a text widget from flat row structure', function (): void {
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
                            ['type' => 'text', 'data' => ['content' => '<p>Hello world</p>']],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $html = (new LayupContent($data))->toHtml();
    expect($html)->toContain('Hello world');
});

it('renders multiple rows', function (): void {
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
                            ['type' => 'text', 'data' => ['content' => 'First row']],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'r2',
                'settings' => [],
                'columns' => [
                    [
                        'id' => 'c2',
                        'span' => 12,
                        'settings' => [],
                        'widgets' => [
                            ['type' => 'text', 'data' => ['content' => 'Second row']],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $html = (new LayupContent($data))->toHtml();
    expect($html)->toContain('First row')
        ->and($html)->toContain('Second row');
});

// --- Sections support ---

it('renders content from sections structure', function (): void {
    $data = [
        'sections' => [
            [
                'settings' => [],
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
                                    ['type' => 'text', 'data' => ['content' => 'From section']],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $html = (new LayupContent($data))->toHtml();
    expect($html)->toContain('From section');
});

it('renders content across multiple sections', function (): void {
    $data = [
        'sections' => [
            [
                'settings' => [],
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
                                    ['type' => 'text', 'data' => ['content' => 'Section one']],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'settings' => [],
                'rows' => [
                    [
                        'id' => 'r2',
                        'settings' => [],
                        'columns' => [
                            [
                                'id' => 'c2',
                                'span' => 12,
                                'settings' => [],
                                'widgets' => [
                                    ['type' => 'text', 'data' => ['content' => 'Section two']],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $html = (new LayupContent($data))->toHtml();
    expect($html)->toContain('Section one')
        ->and($html)->toContain('Section two');
});

// --- getSectionTree ---

it('getSectionTree returns sections with hydrated rows', function (): void {
    $data = [
        'sections' => [
            [
                'settings' => ['background' => 'blue'],
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
                                    ['type' => 'text', 'data' => ['content' => 'test']],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $sections = (new LayupContent($data))->getSectionTree();
    expect($sections)->toHaveCount(1);
    expect($sections[0]['settings'])->toBe(['background' => 'blue']);
    expect($sections[0]['rows'])->toHaveCount(1);
    expect($sections[0]['rows'][0])->toBeInstanceOf(\Crumbls\Layup\View\Row::class);
});

it('getSectionTree wraps flat rows in a single section', function (): void {
    $data = [
        'rows' => [
            [
                'id' => 'r1',
                'settings' => [],
                'columns' => [],
            ],
        ],
    ];

    $sections = (new LayupContent($data))->getSectionTree();
    expect($sections)->toHaveCount(1);
    expect($sections[0]['settings'])->toBe([]);
    expect($sections[0]['rows'])->toHaveCount(1);
});

// --- getContentTree ---

it('getContentTree returns Row objects', function (): void {
    $data = [
        'rows' => [
            [
                'id' => 'r1',
                'settings' => [],
                'columns' => [],
            ],
        ],
    ];

    $tree = (new LayupContent($data))->getContentTree();
    expect($tree)->toHaveCount(1);
    expect($tree[0])->toBeInstanceOf(\Crumbls\Layup\View\Row::class);
});

// --- Unknown widget types ---

it('skips unknown widget types gracefully', function (): void {
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
                            ['type' => 'nonexistent_widget_type', 'data' => ['foo' => 'bar']],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $html = (new LayupContent($data))->toHtml();
    expect($html)->toBeString();
});

it('skips widgets with empty type', function (): void {
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
                            ['type' => '', 'data' => []],
                            ['data' => ['content' => 'no type key']],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $html = (new LayupContent($data))->toHtml();
    expect($html)->toBeString();
});

// --- JSON string input with actual content ---

it('renders content from JSON string', function (): void {
    $data = json_encode([
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
                            ['type' => 'text', 'data' => ['content' => 'From JSON']],
                        ],
                    ],
                ],
            ],
        ],
    ]);

    $html = (new LayupContent($data))->toHtml();
    expect($html)->toContain('From JSON');
});

// --- Empty rows/columns ---

it('handles empty rows array', function (): void {
    $content = new LayupContent(['rows' => []]);
    expect($content->getContentTree())->toBe([]);
    expect($content->toHtml())->toBe('');
});

it('handles row with empty columns', function (): void {
    $data = [
        'rows' => [
            ['id' => 'r1', 'settings' => [], 'columns' => []],
        ],
    ];

    $tree = (new LayupContent($data))->getContentTree();
    expect($tree)->toHaveCount(1);
});

it('handles column with empty widgets', function (): void {
    $data = [
        'rows' => [
            [
                'id' => 'r1',
                'settings' => [],
                'columns' => [
                    ['id' => 'c1', 'span' => 12, 'settings' => [], 'widgets' => []],
                ],
            ],
        ],
    ];

    $html = (new LayupContent($data))->toHtml();
    expect($html)->toBeString();
});
