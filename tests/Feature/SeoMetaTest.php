<?php

declare(strict_types=1);

use Crumbls\Layup\Models\Page;

beforeEach(function (): void {
    config(['layup.frontend.enabled' => true]);
    $prefix = config('layup.frontend.prefix', 'pages');
    $this->routePrefix = $prefix !== '' ? "/{$prefix}" : '';
});

it('emits OG meta even when description is missing', function (): void {
    Page::create([
        'title' => 'No Description',
        'slug' => 'no-description',
        'content' => ['rows' => []],
        'status' => 'published',
    ]);

    $response = $this->get("{$this->routePrefix}/no-description");
    $response->assertStatus(200);

    $content = $response->getContent();
    expect($content)->toContain('property="og:title"')
        ->toContain('property="og:type"')
        ->toContain('property="og:url"')
        ->toContain('rel="canonical"')
        ->toContain('"@type":"WebPage"');
});

it('emits description-dependent meta only when description is set', function (): void {
    Page::create([
        'title' => 'With Desc',
        'slug' => 'with-desc',
        'content' => ['rows' => []],
        'status' => 'published',
        'meta' => ['description' => 'A short summary.'],
    ]);

    $content = $this->get("{$this->routePrefix}/with-desc")->getContent();

    expect($content)->toContain('name="description"')
        ->toContain('A short summary.')
        ->toContain('property="og:description"')
        ->toContain('name="twitter:description"');
});

it('uses summary card without image and summary_large_image with one', function (): void {
    Page::create([
        'title' => 'Plain',
        'slug' => 'plain',
        'content' => ['rows' => []],
        'status' => 'published',
    ]);

    $plain = $this->get("{$this->routePrefix}/plain")->getContent();
    expect($plain)->toContain('name="twitter:card" content="summary"')
        ->not->toContain('summary_large_image');

    Page::create([
        'title' => 'With Image',
        'slug' => 'with-image',
        'content' => ['rows' => []],
        'status' => 'published',
        'featured_image' => 'https://cdn.example.com/img.jpg',
    ]);

    $withImage = $this->get("{$this->routePrefix}/with-image")->getContent();
    expect($withImage)->toContain('name="twitter:card" content="summary_large_image"')
        ->toContain('property="og:image"')
        ->toContain('https://cdn.example.com/img.jpg');
});

it('emits robots noindex when meta.noindex is set', function (): void {
    Page::create([
        'title' => 'Hidden',
        'slug' => 'hidden',
        'content' => ['rows' => []],
        'status' => 'published',
        'meta' => ['noindex' => true],
    ]);

    $content = $this->get("{$this->routePrefix}/hidden")->getContent();
    expect($content)->toContain('name="robots" content="noindex,nofollow"');
});

it('does not emit robots noindex by default', function (): void {
    Page::create([
        'title' => 'Public',
        'slug' => 'public',
        'content' => ['rows' => []],
        'status' => 'published',
    ]);

    $content = $this->get("{$this->routePrefix}/public")->getContent();
    expect($content)->not->toContain('name="robots"');
});

it('emits article timestamps and og:type=article when published_at is set', function (): void {
    Page::create([
        'title' => 'Article',
        'slug' => 'article',
        'content' => ['rows' => []],
        'status' => 'published',
        'published_at' => '2026-04-01 12:00:00',
    ]);

    $content = $this->get("{$this->routePrefix}/article")->getContent();
    expect($content)->toContain('property="og:type" content="article"')
        ->toContain('property="article:published_time"')
        ->toContain('property="article:modified_time"');
});

it('falls back to og:type=website when published_at is null', function (): void {
    // status=draft to bypass the saving hook that auto-fills published_at,
    // then flip to published without re-saving.
    $page = Page::create([
        'title' => 'Static',
        'slug' => 'static',
        'content' => ['rows' => []],
        'status' => 'draft',
    ]);
    $page->newQuery()->whereKey($page->id)->update(['status' => 'published']);

    $content = $this->get("{$this->routePrefix}/static")->getContent();
    expect($content)->toContain('property="og:type" content="website"')
        ->not->toContain('article:published_time');
});

it('emits og:locale from app locale', function (): void {
    Page::create([
        'title' => 'Loc',
        'slug' => 'loc',
        'content' => ['rows' => []],
        'status' => 'published',
    ]);

    $content = $this->get("{$this->routePrefix}/loc")->getContent();
    expect($content)->toContain('property="og:locale"');
});

it('appends configured title suffix', function (): void {
    config(['layup.seo.title_suffix' => ' – Acme']);

    Page::create([
        'title' => 'About',
        'slug' => 'about-suffix',
        'content' => ['rows' => []],
        'status' => 'published',
    ]);

    $content = $this->get("{$this->routePrefix}/about-suffix")->getContent();
    expect($content)->toContain('About – Acme');
});

it('falls back to default OG image when page has none', function (): void {
    config(['layup.seo.default_og_image' => 'https://cdn.example.com/default.jpg']);

    Page::create([
        'title' => 'No Image',
        'slug' => 'no-image',
        'content' => ['rows' => []],
        'status' => 'published',
    ]);

    $content = $this->get("{$this->routePrefix}/no-image")->getContent();
    expect($content)->toContain('property="og:image"')
        ->toContain('https://cdn.example.com/default.jpg')
        ->toContain('summary_large_image');
});

it('renders nothing when no layupPage is shared', function (): void {
    // Component is dropped into a layout outside of any layup-rendered
    // request — AbstractController never fired, layupPage is unset.
    // The component must no-op, not crash, and not leak empty meta tags.
    $rendered = \Illuminate\Support\Facades\Blade::render('<wrap><x-layup-seo /></wrap>');

    expect($rendered)->toContain('<wrap>')
        ->toContain('</wrap>')
        ->not->toContain('<meta')
        ->not->toContain('og:title')
        ->not->toContain('canonical');
});

it('emits BreadcrumbList from parent chain in JSON-LD', function (): void {
    $root = Page::create([
        'title' => 'Docs',
        'slug' => 'docs-bc',
        'content' => ['rows' => []],
        'status' => 'published',
    ]);

    Page::create([
        'title' => 'Install',
        'slug' => 'install',
        'parent_id' => $root->id,
        'content' => ['rows' => []],
        'status' => 'published',
    ]);

    $content = $this->get("{$this->routePrefix}/docs-bc/install")->getContent();
    expect($content)->toContain('"@type":"BreadcrumbList"')
        ->toContain('"name":"Docs"')
        ->toContain('"name":"Install"');
});
