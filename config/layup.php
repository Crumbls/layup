<?php

declare(strict_types=1);
use Crumbls\Layup\Models\Page;
use Crumbls\Layup\View\AccordionWidget;
use Crumbls\Layup\View\AlertWidget;
use Crumbls\Layup\View\AnchorWidget;
use Crumbls\Layup\View\AnimatedHeadingWidget;
use Crumbls\Layup\View\AudioWidget;
use Crumbls\Layup\View\AvatarGroupWidget;
use Crumbls\Layup\View\BackToTopWidget;
use Crumbls\Layup\View\BadgeWidget;
use Crumbls\Layup\View\BannerWidget;
use Crumbls\Layup\View\BarCounterWidget;
use Crumbls\Layup\View\BeforeAfterWidget;
use Crumbls\Layup\View\BlockquoteWidget;
use Crumbls\Layup\View\BlurbWidget;
use Crumbls\Layup\View\BreadcrumbsWidget;
use Crumbls\Layup\View\ButtonWidget;
use Crumbls\Layup\View\CallToActionWidget;
use Crumbls\Layup\View\CardWidget;
use Crumbls\Layup\View\ChangelogWidget;
use Crumbls\Layup\View\CodeWidget;
use Crumbls\Layup\View\ComparisonTableWidget;
use Crumbls\Layup\View\ContactFormWidget;
use Crumbls\Layup\View\ContentToggleWidget;
use Crumbls\Layup\View\CookieConsentWidget;
use Crumbls\Layup\View\CountdownWidget;
use Crumbls\Layup\View\CtaBannerWidget;
use Crumbls\Layup\View\DividerWidget;
use Crumbls\Layup\View\EmbedWidget;
use Crumbls\Layup\View\FaqWidget;
use Crumbls\Layup\View\FeatureGridWidget;
use Crumbls\Layup\View\FeatureListWidget;
use Crumbls\Layup\View\FileDownloadWidget;
use Crumbls\Layup\View\FlipCardWidget;
use Crumbls\Layup\View\GalleryWidget;
use Crumbls\Layup\View\GradientTextWidget;
use Crumbls\Layup\View\HeadingWidget;
use Crumbls\Layup\View\HeroWidget;
use Crumbls\Layup\View\HighlightBoxWidget;
use Crumbls\Layup\View\HotspotWidget;
use Crumbls\Layup\View\HtmlWidget;
use Crumbls\Layup\View\IconBoxWidget;
use Crumbls\Layup\View\IconListWidget;
use Crumbls\Layup\View\IconWidget;
use Crumbls\Layup\View\ImageCardWidget;
use Crumbls\Layup\View\ImageHotspotWidget;
use Crumbls\Layup\View\ImageTextWidget;
use Crumbls\Layup\View\ImageWidget;
use Crumbls\Layup\View\ListWidget;
use Crumbls\Layup\View\LoginWidget;
use Crumbls\Layup\View\LogoGridWidget;
use Crumbls\Layup\View\LogoSliderWidget;
use Crumbls\Layup\View\LottieWidget;
use Crumbls\Layup\View\MapWidget;
use Crumbls\Layup\View\MarqueeWidget;
use Crumbls\Layup\View\MasonryWidget;
use Crumbls\Layup\View\MenuWidget;
use Crumbls\Layup\View\MetricWidget;
use Crumbls\Layup\View\ModalWidget;
use Crumbls\Layup\View\NewsletterWidget;
use Crumbls\Layup\View\NotificationBarWidget;
use Crumbls\Layup\View\NumberCounterWidget;
use Crumbls\Layup\View\PageTitleWidget;
use Crumbls\Layup\View\PersonWidget;
use Crumbls\Layup\View\PostListWidget;
use Crumbls\Layup\View\PriceWidget;
use Crumbls\Layup\View\PricingTableWidget;
use Crumbls\Layup\View\PricingToggleWidget;
use Crumbls\Layup\View\ProgressCircleWidget;
use Crumbls\Layup\View\QuoteCarouselWidget;
use Crumbls\Layup\View\RichTextWidget;
use Crumbls\Layup\View\SearchWidget;
use Crumbls\Layup\View\SectionHeadingWidget;
use Crumbls\Layup\View\SeparatorWidget;
use Crumbls\Layup\View\ShareButtonsWidget;
use Crumbls\Layup\View\SkillBarWidget;
use Crumbls\Layup\View\SliderWidget;
use Crumbls\Layup\View\SocialFollowWidget;
use Crumbls\Layup\View\SocialProofWidget;
use Crumbls\Layup\View\SpacerWidget;
use Crumbls\Layup\View\StarRatingWidget;
use Crumbls\Layup\View\StatCardWidget;
use Crumbls\Layup\View\StepProcessWidget;
use Crumbls\Layup\View\TableOfContentsWidget;
use Crumbls\Layup\View\TableWidget;
use Crumbls\Layup\View\TabsWidget;
use Crumbls\Layup\View\TeamGridWidget;
use Crumbls\Layup\View\TestimonialCarouselWidget;
use Crumbls\Layup\View\TestimonialGridWidget;
use Crumbls\Layup\View\TestimonialSliderWidget;
use Crumbls\Layup\View\TestimonialWidget;
use Crumbls\Layup\View\TextColumnsWidget;
use Crumbls\Layup\View\TextWidget;
use Crumbls\Layup\View\TimelineWidget;
use Crumbls\Layup\View\ToggleWidget;
use Crumbls\Layup\View\TypewriterWidget;
use Crumbls\Layup\View\VideoPlaylistWidget;
use Crumbls\Layup\View\VideoWidget;

return [
    /*
    |--------------------------------------------------------------------------
    | Registered Widgets
    |--------------------------------------------------------------------------
    |
    | Widget classes available in the page builder. Each must extend
    | Crumbls\Layup\View\BaseWidget.
    |
    */
    'widgets' => [
        // Content
        TextWidget::class,
        HeadingWidget::class,
        PageTitleWidget::class,
        BlurbWidget::class,
        IconWidget::class,
        AccordionWidget::class,
        ToggleWidget::class,
        TabsWidget::class,
        PersonWidget::class,
        TestimonialWidget::class,
        NumberCounterWidget::class,
        BarCounterWidget::class,

        // Media
        ImageWidget::class,
        GalleryWidget::class,
        VideoWidget::class,
        AudioWidget::class,
        SliderWidget::class,
        MapWidget::class,

        // Interactive
        ButtonWidget::class,
        CallToActionWidget::class,
        CountdownWidget::class,
        PricingTableWidget::class,
        SocialFollowWidget::class,

        // Layout
        SpacerWidget::class,
        DividerWidget::class,

        // Advanced
        HtmlWidget::class,
        CodeWidget::class,
        EmbedWidget::class,
        AlertWidget::class,
        TableWidget::class,
        ProgressCircleWidget::class,
        MenuWidget::class,
        SearchWidget::class,
        ContactFormWidget::class,
        StarRatingWidget::class,
        LogoGridWidget::class,
        BlockquoteWidget::class,
        FeatureListWidget::class,
        TimelineWidget::class,
        StatCardWidget::class,
        MarqueeWidget::class,
        BeforeAfterWidget::class,
        TeamGridWidget::class,
        NotificationBarWidget::class,
        HeroWidget::class,
        BreadcrumbsWidget::class,
        FaqWidget::class,
        LoginWidget::class,
        NewsletterWidget::class,
        PostListWidget::class,
        SeparatorWidget::class,
        BackToTopWidget::class,
        CookieConsentWidget::class,
        ShareButtonsWidget::class,
        ModalWidget::class,
        TypewriterWidget::class,
        CardWidget::class,
        TableOfContentsWidget::class,
        StepProcessWidget::class,
        GradientTextWidget::class,
        FlipCardWidget::class,
        PricingToggleWidget::class,
        ImageHotspotWidget::class,
        LottieWidget::class,
        MasonryWidget::class,
        RichTextWidget::class,
        ListWidget::class,
        AnchorWidget::class,
        BannerWidget::class,
        ContentToggleWidget::class,
        LogoSliderWidget::class,
        TestimonialSliderWidget::class,
        IconBoxWidget::class,
        AnimatedHeadingWidget::class,
        TestimonialCarouselWidget::class,
        ComparisonTableWidget::class,
        VideoPlaylistWidget::class,
        BadgeWidget::class,
        AvatarGroupWidget::class,
        TestimonialGridWidget::class,
        FileDownloadWidget::class,
        ChangelogWidget::class,
        SkillBarWidget::class,
        PriceWidget::class,
        HotspotWidget::class,
        MetricWidget::class,
        FeatureGridWidget::class,
        HighlightBoxWidget::class,
        SocialProofWidget::class,
        CtaBannerWidget::class,
        IconListWidget::class,
        ImageCardWidget::class,
        ImageTextWidget::class,
        QuoteCarouselWidget::class,
        SectionHeadingWidget::class,
        TextColumnsWidget::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Widget Security
    |--------------------------------------------------------------------------
    |
    | allow_raw_html: when true (default), HtmlWidget, EmbedWidget, and
    | MapWidget render their output unescaped via {!! !!}. Set to false to
    | escape the output and prevent raw HTML injection.
    |
    */
    'allow_raw_html' => true,

    /*
    |--------------------------------------------------------------------------
    | Widget Auto-Discovery
    |--------------------------------------------------------------------------
    |
    | Automatically discovers and registers widget classes from the given
    | namespace/directory. Set to null to disable auto-discovery.
    |
    */
    'widget_discovery' => [
        'namespace' => 'App\\Layup\\Widgets',
        'directory' => null, // defaults to app_path('Layup/Widgets')
    ],

    /*
    |--------------------------------------------------------------------------
    | Uploads
    |--------------------------------------------------------------------------
    |
    | Filesystem disk used for all FileUpload fields in the page builder.
    | Defaults to 'public' so uploaded files are web-accessible via the
    | storage symlink. Change to 's3' or another disk as needed.
    |
    */
    'uploads' => [
        'disk' => 'public',
        'max_size' => 10240,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pages Configuration
    |--------------------------------------------------------------------------
    |
    | Configurable per-dashboard. If you run multiple Filament panels that
    | each need their own page table, override these values per panel.
    |
    */
    'pages' => [
        'table' => 'layup_pages',
        'model' => Page::class,
        'enabled' => true,
        'default_slug' => null,

        /*
        | Maximum nesting depth for parent → child page chains. Used by the
        | HasNestedPath concern to reject deep trees and as a backstop for
        | accidental cycles. Top-level pages count as depth 1.
        */
        'max_depth' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Revisions
    |--------------------------------------------------------------------------
    |
    | Automatically save content revisions when a page is updated.
    | Old revisions are pruned when the count exceeds 'max'.
    |
    */
    'revisions' => [
        'enabled' => true,
        'table' => 'layup_page_revisions',
        'max' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Frontend Rendering
    |--------------------------------------------------------------------------
    |
    | Controls the public-facing page routes. Disable to handle routing
    | yourself, or customize the prefix, middleware, layout, and view.
    |
    | Set 'domain' to serve pages on a specific domain (e.g., for a
    | headless CMS where the frontend lives on a different subdomain).
    |
    */
    'frontend' => [
        'enabled' => true,

        // URL prefix. Use '' or '/' to mount at the site root — these are the
        // only values that activate auto-exclusion of Filament panel paths and
        // framework routes. Other values (including null) are treated as a
        // literal prefix with no auto-exclusion.
        'prefix' => 'pages',

        'middleware' => ['web'],
        'domain' => null,

        // Blade component name passed to <x-dynamic-component>. Examples:
        //   'app'          -> resources/views/components/app.blade.php
        //   'layouts.app'  -> resources/views/components/layouts/app.blade.php
        //   'layouts::app' -> resources/views/layouts/app.blade.php
        //                    (Livewire anonymous namespace — use this with
        //                    the Livewire starter kit)
        'layout' => 'app',

        'view' => 'layup::frontend.page',
        'max_width' => 'container',
        'include_scripts' => true,

        // Additional paths to exclude from the root-mount catch-all.
        // Only applied when 'prefix' is '' or '/'.
        'excluded_paths' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Layout Containers
    |--------------------------------------------------------------------------
    |
    | Named presets that wrap each non-full-width row. A page may override
    | the global default by setting `meta.layout.container` to a preset key.
    |
    | Apps can extend this dictionary in their published config — add or
    | override keys, supply custom Tailwind classes, and the new preset
    | shows up automatically in the Page Settings modal and the safelist.
    |
    | The `default` key applies when a page has no explicit override.
    | If `default` itself is null, layup falls back to `frontend.max_width`
    | for backward compatibility.
    |
    */
    /*
    |--------------------------------------------------------------------------
    | Scheduling
    |--------------------------------------------------------------------------
    |
    | Layup auto-registers the layup:publish-scheduled command on the app's
    | scheduler (every minute) so scheduled pages flip to "published" at
    | their published_at time without any wiring. Set auto_publish to false
    | to register the command yourself, e.g. on a single worker only.
    |
    */
    'scheduling' => [
        'auto_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO Defaults
    |--------------------------------------------------------------------------
    |
    | Layup emits standard meta tags (Open Graph, Twitter, canonical, JSON-LD)
    | from the data each page already provides. These knobs let you set the
    | site-wide fallbacks that don't belong on the page record itself.
    |
    | We deliberately don't ship anything that crosses into "SEO tool"
    | territory — no scoring, no keyword analysis, no SERP previews. If you
    | need that, reach for a dedicated tool.
    |
    */
    'seo' => [
        // Appended to every page's <title>, e.g. ' – Site Name'.
        // null disables the suffix.
        'title_suffix' => null,

        // og:site_name fallback. null falls back to config('app.name').
        'site_name' => null,

        // Path or URL used when a page has no featured image.
        // Path is resolved through layup.uploads.disk; absolute URLs pass through.
        'default_og_image' => null,

        // Label for the root crumb in BreadcrumbList JSON-LD.
        'home_breadcrumb_label' => 'Home',

        // Priority value written into sitemap entries for published pages.
        'sitemap_priority' => '0.7',
    ],

    'page_layout' => [
        'default' => 'container',
        'default_template' => null, // null = use layup.frontend.view
        /*
        | View templates the user can pick per-page in the Page Settings
        | modal. Each value is a Blade view name; the key is what gets
        | stored in meta.layout.template. The package fallback is the
        | global config('layup.frontend.view').
        */
        'templates' => [
            // 'app.layouts.layup-landing' => 'Landing Page',
            // 'app.layouts.layup-sidebar' => 'With Sidebar',
        ],
        'containers' => [
            'container' => [
                'label' => 'Container',
                'classes' => 'container mx-auto px-4',
            ],
            'narrow' => [
                'label' => 'Narrow',
                'classes' => 'max-w-3xl mx-auto px-4',
            ],
            'wide' => [
                'label' => 'Wide',
                'classes' => 'max-w-7xl mx-auto px-4',
            ],
            'full' => [
                'label' => 'Full width',
                'classes' => 'w-full',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tailwind Safelist
    |--------------------------------------------------------------------------
    |
    | Layup generates Tailwind utility classes dynamically (column widths,
    | gap values, user-defined classes). Since Tailwind can't scan database
    | content, these classes are written to a safelist file.
    |
    | When 'auto_sync' is enabled, saving a page automatically regenerates
    | the safelist. If new classes are detected, a SafelistChanged event
    | is dispatched so you can trigger a frontend rebuild.
    |
    | When using Layup on your own models, list each model and its content
    | column in 'content_sources'. These sources are scanned in addition to
    | bundled Pages (when enabled).
    |
    | Run `php artisan layup:safelist` to manually regenerate.
    |
    */
    'safelist' => [
        'enabled' => true,
        'auto_sync' => true,
        'path' => 'storage/layup-safelist.txt',
        'extra_classes' => [], // Additional classes to always include in the safelist
        'content_sources' => [
            // ['model' => App\Models\Post::class, 'column' => 'content'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Breakpoints
    |--------------------------------------------------------------------------
    |
    | Responsive preview breakpoints shown in the size toggler.
    |
    */
    'breakpoints' => [
        'sm' => ['label' => 'sm', 'width' => 640, 'icon' => 'heroicon-o-device-phone-mobile'],
        'md' => ['label' => 'md', 'width' => 768, 'icon' => 'heroicon-o-device-tablet'],
        'lg' => ['label' => 'lg', 'width' => 1024, 'icon' => 'heroicon-o-computer-desktop'],
        'xl' => ['label' => 'xl', 'width' => 1280, 'icon' => 'heroicon-o-tv'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Breakpoint
    |--------------------------------------------------------------------------
    */
    'default_breakpoint' => 'lg',

    /*
    |--------------------------------------------------------------------------
    | Row Templates
    |--------------------------------------------------------------------------
    |
    | Predefined column layouts for the "Add Row" picker.
    | Each is an array of column spans (must sum to 12).
    |
    */
    'row_templates' => [
        [12],
        [6, 6],
        [4, 4, 4],
        [3, 3, 3, 3],
        [8, 4],
        [4, 8],
        [3, 6, 3],
        [2, 8, 2],
    ],
];
