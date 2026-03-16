<?php

declare(strict_types=1);

namespace Crumbls\Layup\Models;

use Crumbls\Layup\Concerns\HasLayupContent;
use Crumbls\Layup\Support\ContentValidator;
use Crumbls\Layup\Support\SafelistCollector;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, HasLayupContent, SoftDeletes;

    protected static function booted(): void
    {
        static::saving(function (Page $page): bool {
            if (! $page->isDirty('content')) {
                return true;
            }

            $content = $page->content;
            if (! is_array($content)) {
                return true;
            }

            $result = (new ContentValidator)->validate($content);

            if (! $result->passes()) {
                // Log warnings but don't block save — widget data issues are soft errors
                logger()->warning('Layup page content validation warnings', [
                    'page_id' => $page->id,
                    'slug' => $page->slug,
                    'errors' => $result->errors(),
                ]);
            }

            return true;
        });

        static::saved(function (Page $page): void {
            if (config('layup.safelist.enabled') && config('layup.safelist.auto_sync')) {
                SafelistCollector::sync();
            }

            // Auto-save revision when content changes
            if ($page->wasChanged('content') && config('layup.revisions.enabled', true)) {
                $page->saveRevision();
            }
        });

        static::deleted(function (Page $page): void {
            if (config('layup.safelist.enabled') && config('layup.safelist.auto_sync')) {
                SafelistCollector::sync();
            }
        });
    }

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'meta',
    ];

    /**
     * Use configurable table name so multiple dashboards can each
     * point to their own pages table.
     */
    public function getTable(): string
    {
        return config('layup.pages.table', 'layup_pages');
    }

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'meta' => 'array',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /*
    |--------------------------------------------------------------------------
    | SEO Helpers
    |--------------------------------------------------------------------------
    */

    public function getMetaTitle(): string
    {
        return $this->meta['title'] ?? $this->title ?? '';
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta['description'] ?? null;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->meta['keywords'] ?? null;
    }

    /**
     * Get all meta as a flat array suitable for <meta> tags.
     */
    public function getMetaTags(): array
    {
        return array_filter([
            'title' => $this->getMetaTitle(),
            'description' => $this->getMetaDescription(),
            'keywords' => $this->getMetaKeywords(),
        ]);
    }

    /**
     * Get structured data (JSON-LD) for this page.
     *
     * Supports WebPage, Article, FAQPage, and BreadcrumbList schemas.
     * Set `meta.schema_type` to 'Article' or 'FAQPage' to change type.
     */
    public function getStructuredData(): array
    {
        $schemas = [];
        $type = $this->meta['schema_type'] ?? 'WebPage';

        // Main page schema
        $page = array_filter([
            '@context' => 'https://schema.org',
            '@type' => $type,
            'name' => $this->getMetaTitle(),
            'description' => $this->getMetaDescription(),
            'url' => $this->getUrl(),
            'datePublished' => $this->created_at?->toIso8601String(),
            'dateModified' => $this->updated_at?->toIso8601String(),
            'image' => $this->meta['image'] ?? null,
        ]);

        // Article-specific fields
        if (($type === 'Article' || $type === 'BlogPosting') && ! empty($this->meta['author'])) {
            $page['author'] = [
                '@type' => 'Person',
                'name' => $this->meta['author'],
            ];
        }

        $schemas[] = $page;

        // FAQ schema — auto-detect from accordion/toggle widgets
        if ($type === 'FAQPage') {
            $faqs = $this->extractFaqItems();
            if ($faqs !== []) {
                $schemas[0]['mainEntity'] = array_map(fn (array $faq): array => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ],
                ], $faqs);
            }
        }

        // BreadcrumbList
        $breadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ],
        ];

        $slugParts = explode('/', $this->slug);
        $pos = 2;
        $prefix = config('layup.frontend.prefix', 'pages');
        $path = $prefix;
        foreach ($slugParts as $i => $part) {
            $path .= '/' . $part;
            $item = ['@type' => 'ListItem', 'position' => $pos++, 'name' => ucfirst($part)];
            if ($i < count($slugParts) - 1) {
                $item['item'] = url(ltrim($path, '/'));
            }
            $breadcrumbs['itemListElement'][] = $item;
        }

        $schemas[] = $breadcrumbs;

        return $schemas;
    }

    /**
     * Extract FAQ items from accordion/toggle widgets in content.
     */
    protected function extractFaqItems(): array
    {
        $faqs = [];
        $rows = $this->getLayupContent()['rows'] ?? [];

        // Also check inside sections
        $content = $this->getLayupContent();

        if (! empty($content['sections'])) {
            $rows = [];
            foreach ($content['sections'] as $section) {
                foreach ($section['rows'] ?? [] as $row) {
                    $rows[] = $row;
                }
            }
        }

        foreach ($rows as $row) {
            foreach ($row['columns'] ?? [] as $col) {
                foreach ($col['widgets'] ?? [] as $widget) {
                    $type = $widget['type'] ?? '';
                    if (in_array($type, ['accordion', 'toggle'])) {
                        foreach ($widget['data']['items'] ?? [] as $item) {
                            if (! empty($item['title']) && ! empty($item['content'])) {
                                $faqs[] = [
                                    'question' => $item['title'],
                                    'answer' => strip_tags((string) $item['content']),
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $faqs;
    }

    /*
    |--------------------------------------------------------------------------
    | URL Generation
    |--------------------------------------------------------------------------
    */

    /**
     * Get all revisions for this page.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(PageRevision::class, 'page_id')->latest('created_at');
    }

    /**
     * Save a revision of the current content.
     */
    public function saveRevision(?string $note = null): PageRevision
    {
        $maxRevisions = config('layup.revisions.max', 50);

        $revision = $this->revisions()->create([
            'content' => $this->content,
            'note' => $note,
            'author' => auth()->user()?->name ?? auth()->user()?->email,
            'created_at' => now(),
        ]);

        // Prune old revisions
        $count = $this->revisions()->count();
        if ($count > $maxRevisions) {
            $this->revisions()
                ->oldest('created_at')
                ->limit($count - $maxRevisions)
                ->delete();
        }

        return $revision;
    }

    /**
     * Restore content from a revision.
     */
    public function restoreRevision(PageRevision $revision): void
    {
        $this->content = $revision->content;
        $this->save();
    }

    /**
     * Get the public-facing URL for this page.
     */
    public function getUrl(): string
    {
        $prefix = config('layup.frontend.prefix', 'pages');

        return url(ltrim("{$prefix}/{$this->slug}", '/'));
    }

    /*
    |--------------------------------------------------------------------------
    | Factory
    |--------------------------------------------------------------------------
    */

    protected static function newFactory()
    {
        return \Crumbls\Layup\Database\Factories\PageFactory::new();
    }

    /**
     * Get sitemap entries for all published pages.
     * Returns an array of [url, lastmod, priority] suitable for sitemap generation.
     *
     * @return array<array{url: string, lastmod: string, priority: string}>
     */
    public static function sitemapEntries(): array
    {
        return static::published()->get()->map(fn (self $page): array => [
            'url' => $page->getUrl(),
            'lastmod' => $page->updated_at->toDateString(),
            'priority' => '0.7',
        ])->all();
    }
}
