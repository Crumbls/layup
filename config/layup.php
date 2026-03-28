<?php

declare(strict_types=1);

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
        \Crumbls\Layup\View\TextWidget::class,
        \Crumbls\Layup\View\HeadingWidget::class,
        \Crumbls\Layup\View\BlurbWidget::class,
        \Crumbls\Layup\View\IconWidget::class,
        \Crumbls\Layup\View\AccordionWidget::class,
        \Crumbls\Layup\View\ToggleWidget::class,
        \Crumbls\Layup\View\TabsWidget::class,
        \Crumbls\Layup\View\PersonWidget::class,
        \Crumbls\Layup\View\TestimonialWidget::class,
        \Crumbls\Layup\View\NumberCounterWidget::class,
        \Crumbls\Layup\View\BarCounterWidget::class,

        // Media
        \Crumbls\Layup\View\ImageWidget::class,
        \Crumbls\Layup\View\GalleryWidget::class,
        \Crumbls\Layup\View\VideoWidget::class,
        \Crumbls\Layup\View\AudioWidget::class,
        \Crumbls\Layup\View\SliderWidget::class,
        \Crumbls\Layup\View\MapWidget::class,

        // Interactive
        \Crumbls\Layup\View\ButtonWidget::class,
        \Crumbls\Layup\View\CallToActionWidget::class,
        \Crumbls\Layup\View\CountdownWidget::class,
        \Crumbls\Layup\View\PricingTableWidget::class,
        \Crumbls\Layup\View\SocialFollowWidget::class,

        // Layout
        \Crumbls\Layup\View\SpacerWidget::class,
        \Crumbls\Layup\View\DividerWidget::class,

        // Advanced
        \Crumbls\Layup\View\HtmlWidget::class,
        \Crumbls\Layup\View\CodeWidget::class,
        \Crumbls\Layup\View\EmbedWidget::class,
        \Crumbls\Layup\View\AlertWidget::class,
        \Crumbls\Layup\View\TableWidget::class,
        \Crumbls\Layup\View\ProgressCircleWidget::class,
        \Crumbls\Layup\View\MenuWidget::class,
        \Crumbls\Layup\View\SearchWidget::class,
        \Crumbls\Layup\View\ContactFormWidget::class,
        \Crumbls\Layup\View\StarRatingWidget::class,
        \Crumbls\Layup\View\LogoGridWidget::class,
        \Crumbls\Layup\View\BlockquoteWidget::class,
        \Crumbls\Layup\View\FeatureListWidget::class,
        \Crumbls\Layup\View\TimelineWidget::class,
        \Crumbls\Layup\View\StatCardWidget::class,
        \Crumbls\Layup\View\MarqueeWidget::class,
        \Crumbls\Layup\View\BeforeAfterWidget::class,
        \Crumbls\Layup\View\TeamGridWidget::class,
        \Crumbls\Layup\View\NotificationBarWidget::class,
        \Crumbls\Layup\View\HeroWidget::class,
        \Crumbls\Layup\View\BreadcrumbsWidget::class,
        \Crumbls\Layup\View\FaqWidget::class,
        \Crumbls\Layup\View\LoginWidget::class,
        \Crumbls\Layup\View\NewsletterWidget::class,
        \Crumbls\Layup\View\PostListWidget::class,
        \Crumbls\Layup\View\SeparatorWidget::class,
        \Crumbls\Layup\View\BackToTopWidget::class,
        \Crumbls\Layup\View\CookieConsentWidget::class,
        \Crumbls\Layup\View\ShareButtonsWidget::class,
        \Crumbls\Layup\View\ModalWidget::class,
        \Crumbls\Layup\View\TypewriterWidget::class,
        \Crumbls\Layup\View\CardWidget::class,
        \Crumbls\Layup\View\TableOfContentsWidget::class,
        \Crumbls\Layup\View\StepProcessWidget::class,
        \Crumbls\Layup\View\GradientTextWidget::class,
        \Crumbls\Layup\View\FlipCardWidget::class,
        \Crumbls\Layup\View\PricingToggleWidget::class,
        \Crumbls\Layup\View\ImageHotspotWidget::class,
        \Crumbls\Layup\View\LottieWidget::class,
        \Crumbls\Layup\View\MasonryWidget::class,
        \Crumbls\Layup\View\RichTextWidget::class,
        \Crumbls\Layup\View\ListWidget::class,
        \Crumbls\Layup\View\AnchorWidget::class,
        \Crumbls\Layup\View\BannerWidget::class,
        \Crumbls\Layup\View\ContentToggleWidget::class,
        \Crumbls\Layup\View\LogoSliderWidget::class,
        \Crumbls\Layup\View\TestimonialSliderWidget::class,
        \Crumbls\Layup\View\IconBoxWidget::class,
        \Crumbls\Layup\View\AnimatedHeadingWidget::class,
        \Crumbls\Layup\View\TestimonialCarouselWidget::class,
        \Crumbls\Layup\View\ComparisonTableWidget::class,
        \Crumbls\Layup\View\VideoPlaylistWidget::class,
        \Crumbls\Layup\View\BadgeWidget::class,
        \Crumbls\Layup\View\AvatarGroupWidget::class,
        \Crumbls\Layup\View\TestimonialGridWidget::class,
        \Crumbls\Layup\View\FileDownloadWidget::class,
        \Crumbls\Layup\View\ChangelogWidget::class,
        \Crumbls\Layup\View\SkillBarWidget::class,
        \Crumbls\Layup\View\PriceWidget::class,
        \Crumbls\Layup\View\HotspotWidget::class,
        \Crumbls\Layup\View\MetricWidget::class,
        \Crumbls\Layup\View\FeatureGridWidget::class,
        \Crumbls\Layup\View\HighlightBoxWidget::class,
        \Crumbls\Layup\View\SocialProofWidget::class,
        \Crumbls\Layup\View\CtaBannerWidget::class,
        \Crumbls\Layup\View\IconListWidget::class,
        \Crumbls\Layup\View\ImageCardWidget::class,
        \Crumbls\Layup\View\ImageTextWidget::class,
        \Crumbls\Layup\View\QuoteCarouselWidget::class,
        \Crumbls\Layup\View\SectionHeadingWidget::class,
        \Crumbls\Layup\View\TextColumnsWidget::class,
    ],

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
        'model' => \Crumbls\Layup\Models\Page::class,
        'default_slug' => null,
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
        'prefix' => 'pages',
        'middleware' => ['web'],
        'domain' => null,
        'layout' => 'app',
        'view' => 'layup::frontend.page',
        'max_width' => 'container',
        'include_scripts' => true,
        'excluded_paths' => [],
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
    | Run `php artisan layup:safelist` to manually regenerate.
    |
    */
    'safelist' => [
        'enabled' => true,
        'auto_sync' => true,
        'path' => 'storage/layup-safelist.txt',
        'extra_classes' => [], // Additional classes to always include in the safelist
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
