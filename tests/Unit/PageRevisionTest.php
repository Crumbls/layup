<?php

declare(strict_types=1);

use Crumbls\Layup\Models\PageRevision;

it('has correct table name', function (): void {
    $revision = new PageRevision;
    expect($revision->getTable())->toBe('layup_page_revisions');
});

it('reads table name from config', function (): void {
    config(['layup.revisions.table' => 'custom_layup_revisions']);

    $revision = new PageRevision;

    expect($revision->getTable())->toBe('custom_layup_revisions');
});

it('has timestamps disabled', function (): void {
    $revision = new PageRevision;
    expect($revision->timestamps)->toBeFalse();
});

it('has expected fillable fields', function (): void {
    $revision = new PageRevision;
    expect($revision->getFillable())->toBe([
        'page_id',
        'content',
        'note',
        'author',
        'created_at',
    ]);
});

it('casts content to array', function (): void {
    $casts = (new PageRevision)->getCasts();
    expect($casts['content'])->toBe('array');
});

it('casts created_at to datetime', function (): void {
    $casts = (new PageRevision)->getCasts();
    expect($casts['created_at'])->toBe('datetime');
});

it('has page relationship method', function (): void {
    $revision = new PageRevision;
    expect(method_exists($revision, 'page'))->toBeTrue();
});
