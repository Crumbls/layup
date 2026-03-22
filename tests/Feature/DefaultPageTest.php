<?php

declare(strict_types=1);

use Crumbls\Layup\Models\Page;

beforeEach(function (): void {
    config(['layup.frontend.enabled' => true]);
});

it('serves the configured default page when no slug is provided', function (): void {
    $prefix = config('layup.frontend.prefix', 'pages');

    Page::create([
        'title' => 'Welcome Home',
        'slug' => 'home',
        'content' => ['rows' => [[
            'id' => 'r1',
            'settings' => [],
            'columns' => [[
                'id' => 'c1',
                'span' => ['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12],
                'settings' => [],
                'widgets' => [[
                    'id' => 'w1',
                    'type' => 'text',
                    'data' => ['content' => '<p>Welcome to our site</p>'],
                ]],
            ]],
        ]]],
        'status' => 'published',
    ]);

    config(['layup.pages.default_slug' => 'home']);

    $this->get("/{$prefix}")
        ->assertStatus(200)
        ->assertSee('Welcome to our site');
});

it('falls back to empty slug when default_slug is null', function (): void {
    $prefix = config('layup.frontend.prefix', 'pages');

    Page::create([
        'title' => 'Root Page',
        'slug' => '',
        'content' => ['rows' => [[
            'id' => 'r1',
            'settings' => [],
            'columns' => [[
                'id' => 'c1',
                'span' => ['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12],
                'settings' => [],
                'widgets' => [[
                    'id' => 'w1',
                    'type' => 'text',
                    'data' => ['content' => '<p>Root content</p>'],
                ]],
            ]],
        ]]],
        'status' => 'published',
    ]);

    config(['layup.pages.default_slug' => null]);

    $this->get("/{$prefix}")
        ->assertStatus(200)
        ->assertSee('Root content');
});

it('returns 404 when default page does not exist', function (): void {
    $prefix = config('layup.frontend.prefix', 'pages');

    config(['layup.pages.default_slug' => 'nonexistent-home']);

    $this->get("/{$prefix}")->assertStatus(404);
});

it('returns 404 when default page is draft', function (): void {
    $prefix = config('layup.frontend.prefix', 'pages');

    Page::create([
        'title' => 'Draft Home',
        'slug' => 'home',
        'content' => ['rows' => []],
        'status' => 'draft',
    ]);

    config(['layup.pages.default_slug' => 'home']);

    $this->get("/{$prefix}")->assertStatus(404);
});

it('ignores default_slug when an explicit slug is provided', function (): void {
    $prefix = config('layup.frontend.prefix', 'pages');

    Page::create([
        'title' => 'Home',
        'slug' => 'home',
        'content' => ['rows' => [[
            'id' => 'r1',
            'settings' => [],
            'columns' => [[
                'id' => 'c1',
                'span' => ['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12],
                'settings' => [],
                'widgets' => [[
                    'id' => 'w1',
                    'type' => 'text',
                    'data' => ['content' => '<p>Home page</p>'],
                ]],
            ]],
        ]]],
        'status' => 'published',
    ]);

    Page::create([
        'title' => 'About Us',
        'slug' => 'about',
        'content' => ['rows' => [[
            'id' => 'r1',
            'settings' => [],
            'columns' => [[
                'id' => 'c1',
                'span' => ['sm' => 12, 'md' => 12, 'lg' => 12, 'xl' => 12],
                'settings' => [],
                'widgets' => [[
                    'id' => 'w1',
                    'type' => 'text',
                    'data' => ['content' => '<p>About us content</p>'],
                ]],
            ]],
        ]]],
        'status' => 'published',
    ]);

    config(['layup.pages.default_slug' => 'home']);

    $this->get("/{$prefix}/about")
        ->assertStatus(200)
        ->assertSee('About us content')
        ->assertDontSee('Home page');
});

it('returns 404 at index when no default is configured and no empty-slug page exists', function (): void {
    $prefix = config('layup.frontend.prefix', 'pages');

    config(['layup.pages.default_slug' => null]);

    $this->get("/{$prefix}")->assertStatus(404);
});
