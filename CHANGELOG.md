# Changelog

All notable changes to Layup will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

### Fixed

- `layup:install` now runs only Layup's own Pages migrations instead of every pending application migration, skips storage linking for remote upload disks, and resolves dot and Livewire layout names correctly.
- Field-only installs can disable both bundled Pages features, skip Pages migrations, and configure host models as Tailwind safelist content sources.
- Widget auto-discovery now runs when a Filament panel and Layup's developer commands initialize the registry.
- Removed a leftover browser console log from the builder initializer.

## [1.5.0](https://github.com/Crumbls/layup/compare/v1.4.0...v1.5.0) (2026-07-01)


### Features

* Add drag from picker to canvas ([7bea391](https://github.com/Crumbls/layup/commit/7bea3910df9e95e44b283e8ce5b09d3dd940405f))
* add icon picker UI for Blurb widget - Replace plain text input with searchable Select dropdown - 90+ categorized heroicon options with emoji previews - Organized by: arrows, actions, communication, content, business, people, objects, tech, location, time, UI, status ([3a3b72b](https://github.com/Crumbls/layup/commit/3a3b72b248e38a70f9227d95a8730499d7938704))
* Add inline text editing for text/heading widgets ([2451ce0](https://github.com/Crumbls/layup/commit/2451ce08f526772271db59ff010543c7566af8c1))
* Add recently used widgets to picker modal ([afda73d](https://github.com/Crumbls/layup/commit/afda73d77e6b35fbe3285a608cfe26af18d3b262))
* Add revision browser UI to EditPage ([4abbb07](https://github.com/Crumbls/layup/commit/4abbb077f76f8053d959cf3b4c1e7c7b7edd8872))
* Add visual column resize handles ([9a620bb](https://github.com/Crumbls/layup/commit/9a620bb5cff167991441ba8a203dd58534f1809e))
* Add widget icon rendering in picker modal ([51826ee](https://github.com/Crumbls/layup/commit/51826ee361606b988673beac496e5491f8b8486d))
* **core:** add option to hide layup "pages" resource in filament panel ([f6ddd9a](https://github.com/Crumbls/layup/commit/f6ddd9a7b4ced76b60b66168b348bd25324a3a3b))
* enhance LayupContent with Blade directive, trait cleanup, and tests ([c812aeb](https://github.com/Crumbls/layup/commit/c812aeb144050816ef7c4adc775489db6d56cad2))
* Improve widget preview in builder canvas ([be66dde](https://github.com/Crumbls/layup/commit/be66ddebc89403302abe6cfadd8a7214ecb7c4fa))
* register 7 missing widgets, remove gap !important hack, add content validation on save ([6170a66](https://github.com/Crumbls/layup/commit/6170a6605cd163f646f0a14621c12897a84e364b))
* **ui:** helper class for layup content rendering ([2434337](https://github.com/Crumbls/layup/commit/2434337e8840ec67235f76acfe8a060c919b4465))
* v1.4.0 — security hardening, safelist bug fix, dedup & polish ([db5cd68](https://github.com/Crumbls/layup/commit/db5cd68b3f242933dcb4bf3044c988f30b33f620))


### Bug Fixes

* Add live-on-blur validation to widget slideOver forms ([7b89d57](https://github.com/Crumbls/layup/commit/7b89d57f7c9c63ee33612d89b9993d016e1514d2))
* avatar-group blade handles array and string avatar formats ([9d33302](https://github.com/Crumbls/layup/commit/9d33302c3c95da3d7a0c2f3c130bf7f28d65ac2a))
* blade templates handle array and URL image formats in logo-grid, logo-slider, gallery, masonry, team-grid ([d6c97e0](https://github.com/Crumbls/layup/commit/d6c97e0bae56e225631e67d2df307992ae0d55b8))
* Centralize FileUpload disk config for all builder forms ([9620cab](https://github.com/Crumbls/layup/commit/9620cab9032f65d32f12ce6dc3addaa7ed28db14))
* correct class name typo (dLayupContent -&gt; LayupContent) ([b2a0ba1](https://github.com/Crumbls/layup/commit/b2a0ba157374e68688389556d3a90f1cbe9e40f2))
* Remove hardcoded rounded-lg from slider, use Design tab border_radius ([6ae2aaa](https://github.com/Crumbls/layup/commit/6ae2aaaaf0a085c5f9d2e697f4e1804a41ffdf7a))
* Render slider rich content as unescaped HTML ([4187fbd](https://github.com/Crumbls/layup/commit/4187fbd679d4ae19a556f3df97834cfbad996598))
* restore undo/redo Livewire sync (v1.4.1) ([4fb84a2](https://github.com/Crumbls/layup/commit/4fb84a21c2f66e2099ed8873ebc07225b8c84128))
* ship stubs/ in Composer dist tarball ([d603bb3](https://github.com/Crumbls/layup/commit/d603bb33b5b042baa1f586689e474c1d3be9eead))
* Slider slides now fill parent height with absolute positioning ([7823822](https://github.com/Crumbls/layup/commit/7823822c73928fb889b1364b4941a9ad5b05a10e))
* **ui:** page builder with locale key ([dbd60ba](https://github.com/Crumbls/layup/commit/dbd60ba0c1b44f5163649a2308f1862860b86b99))

## [1.4.0](https://github.com/Crumbls/layup/compare/v1.3.1...v1.4.0) (2026-06-23)


### Features

* v1.4.0 — security hardening, safelist bug fix, dedup & polish ([db5cd68](https://github.com/Crumbls/layup/commit/db5cd68b3f242933dcb4bf3044c988f30b33f620))


### Bug Fixes

* restore undo/redo Livewire sync (v1.4.1) ([4fb84a2](https://github.com/Crumbls/layup/commit/4fb84a21c2f66e2099ed8873ebc07225b8c84128))

## [1.4.1] - 2026-05-29

### Fixed
- Undo/redo (Ctrl+Z / Ctrl+Y) now correctly syncs restored state back to Livewire. Previously `$wire.restoreContent()` was called but the method did not exist, so the canvas appeared to undo visually but the field saved the wrong content on form submit.
- Column resize drag now adds a history snapshot on release — resize operations are undoable.
- Inline double-click text edits now push a history snapshot and sync correctly to Livewire state.
- Removed stray `console.log` debug statement in inline edit handler.

## [1.4.0] - 2026-05-29

### Fixed
- SafelistCollector now correctly collects custom classes and inline styles from section-structured pages (previously only legacy `rows` structure was scanned).

### Security
- Import action path-traversal hardening: the uploaded file path is now validated to reject `..`, null bytes, and absolute paths before any disk operation.
- Added configurable `layup.uploads.max_size` (default 10240 KB) applied to all FileUpload fields to prevent unbounded uploads.
- `ImageWidget::onDelete` now uses `config('layup.uploads.disk')` instead of hardcoded `public` disk, and validates the path before deletion.
- Added `layup.allow_raw_html` config flag (default `true`). Set to `false` to escape HTML/embed/map widget output rather than rendering it raw.
- Import exceptions are now logged via `logger()->warning()` for diagnostics (user-facing message unchanged).

### Changed
- `Page` status strings extracted to class constants (`Page::STATUS_PUBLISHED`, `Page::STATUS_DRAFT`, `Page::STATUS_SCHEDULED`).
- Sitemap entry priority is now configurable via `layup.seo.sitemap_priority` (default `'0.7'`).
- `SafelistCollector::writeFile` now uses the `File` facade (`File::ensureDirectoryExists`, `File::put`) and logs write failures.
- `HasLayupContent` and `LayupContent` now use `ContentWalker::extractRows()` for all row-extraction paths, eliminating drift between the two implementations.
- `HasLayupContent::buildRowTree` now guards against non-string widget type values (matches `LayupContent`).
- Scope methods `scopePublished`, `scopeDraft`, `scopeScheduled` now have proper `Builder` type hints and return types.

## [1.3.1](https://github.com/Crumbls/layup/compare/v1.3.0...v1.3.1) (2026-05-02)

### Documentation

- **Reframed docs around the `LayupBuilder` field as the core primitive.** Previous docs led with "page builder"; the field-as-primitive story (drop `LayupBuilder::make()` into any Filament form) was buried. README, `docs/introduction.md`, and `docs/installation.md` now lead with the field, with the bundled Pages resource positioned as one application of it.
- New **`docs/embedding-the-field.md`** -- end-to-end walkthrough for adding `LayupBuilder` to any Eloquent model: JSON column migration, `HasLayupContent` trait, form integration, and three rendering paths (`@layup` directive, `toHtml()`, `<x-layup-widget>`).
- New **`docs/field-only-installation.md`** -- minimal install path for users who want the field without the bundled Pages resource. Sets `pages.enabled = false`, skips the resource, keeps the safelist + theme + plugin API.
- New **`docs/customization/filament-plugin-api.md`** -- complete reference for every fluent method on `LayupPlugin` (`->widgets()`, `->withoutWidgets()`, `->withoutConfigWidgets()`, `->colors()`, `->darkColors()`, `->fonts()`, `->borderRadius()`, `->withoutPanelColors()`). Previously these methods were only listed in API reference.
- New **`docs/customization/swapping-the-page-model.md`** -- consolidated guide for using a custom Page model. Lifted the buried "Multiple dashboards" section out of `rendering-content.md` and expanded it with multi-panel patterns and when-to-use-instead-of-embed-the-field guidance.
- New **`docs/customization/disable-pages-resource.md`** -- partner doc to field-only install. Documents what disappears when `pages.enabled = false`, what still works (field, registry, theme, commands), and optional schema cleanup.
- Renamed **`docs/advanced/` to `docs/customization/`** -- the directory's contents were customization guides; the new name reflects that. All 12 existing files moved via `git mv` (history preserved). Internal cross-links and the absolute deployed URL in `api-reference/widget-contract.md` updated.
- Rewrote **`docs/customization/_index.md`** from a 6-line stub into a customization map with an "I want to..." quickref table covering every customization point, including links to embedding the field, plugin API, theme, page templates, SEO, and testing.
- Expanded the **`SafelistChanged` event** documentation in `docs/customization/tailwind-safelist.md` with realistic listener examples (CI rebuild trigger, CDN purge, async job dispatch).
- Added **"Choose your install path"** section to `docs/installation.md` with both the full Pages-resource path and the field-only path. Inlined a `LayupBuilder::make('content')` snippet so the core API is visible within the first 30 lines.
- Added **troubleshooting section** to `docs/installation.md` covering the seven most common `layup:doctor` failures (plugin registration, missing migrations, storage symlink, layout component, `@layupScripts` directive, safelist file, upload disk) with one-line fix recipes.
- Added **"Map: which key for which job"** intent-grouped table to the top of `docs/configuration.md` so readers can jump to the section they need without scrolling the flat list.
- Linked **CHANGELOG** from `docs/installation.md` for upgrade guidance.

### Notes

- The `docs/advanced/` -> `docs/customization/` rename changes the public docs URL from `/documentation/layup/v1/advanced/*` to `/documentation/layup/v1/customization/*`. Set up redirects on the docs site (`/advanced/* -> /customization/*`) before deploying to preserve external links and SEO equity on the old paths.
- No code changes in this release. All updates are documentation only. Existing installations require no action.

## [1.3.0](https://github.com/Crumbls/layup/compare/v1.2.3...v1.3.0) (2026-05-01)

### Added
- **Dual-render widget architecture.** Widgets can now render through either a Blade view component (the default) or a Livewire component, opt-in via a new `BaseLivewireWidget` base class. Same `Widget` contract, same editor experience, same registration -- only the frontend render path differs.
  - `Crumbls\Layup\View\BaseLivewireWidget` mounts a Livewire component via `<livewire:dynamic-component>`, passing the widget's `$data` as a prop and recursively-rendered children as the default slot. Children remain polymorphic (Blade, Livewire, or any mix).
  - `Crumbls\Layup\View\BaseBladeWidget` is the renamed body of the previous `BaseWidget`. The default Blade rendering path is unchanged.
  - `Crumbls\Layup\View\BaseWidget` remains as an abstract alias of `BaseBladeWidget` so every existing widget, stub, doc, and downstream package continues to work without modification.
  - `Crumbls\Layup\View\Concerns\HasWidgetDefaults` trait extracts the static metadata defaults shared between both bases.
  - `Crumbls\Layup\View\Concerns\Identity\` ships per-widget identity traits (`HeadingIdentity`, `ButtonIdentity`, `NumberCounterIdentity`, `NewsletterIdentity`) so a downstream package can ship a Livewire flavour of those built-ins without redeclaring form schema, defaults, or preview.
  - `BaseView::renderChildrenToHtml()` recursively renders all children to a single HTML string. Used internally by `BaseLivewireWidget` for the slot path and available to any custom render path.
  - `livewire/livewire` is a soft dependency (declared in `composer.json` `suggest`). The package loads cleanly without it; install it only when actually using `BaseLivewireWidget`.
  - New `docs/advanced/livewire-widgets.md` covering when to choose each base, the render flow, the slot-based children model, identity-trait swapping, asset pipeline, performance, testing, and security considerations.
- New `<x-layup-seo />` Blade component. Drop it once into your layout's `<head>` and Layup emits the full meta block (description, OG, Twitter, canonical, robots, article timestamps, JSON-LD) on every layup-rendered request. On non-layup routes the component renders nothing, so it's safe in shared layouts.
- `Crumbls\Layup\Http\Controllers\AbstractController` now shares the resolved record as `layupPage` in view scope, so the component resolves the page automatically on any host layout. Custom controllers can pass `:page="$myPage"` explicitly.
- Page Settings modal exposes Meta Description (160-char) and a "Hide from search engines" toggle (`meta.noindex`).
- New `layup.seo` config block: `title_suffix`, `site_name`, `default_og_image`, `home_breadcrumb_label`.
- `og:locale`, `og:site_name`, `article:published_time`, `article:modified_time` emitted automatically.
- BreadcrumbList JSON-LD walks the parent chain with real page titles when a parent_id is set; legacy slug-with-slashes pages still get a path-derived breadcrumb with the page's actual title at the leaf.
- `tests/Feature/SeoMetaTest.php` adds render-level coverage, including a no-op assertion when `<x-layup-seo />` runs outside a Layup request.
- `docs/advanced/seo-meta.md` documents the component, per-page settings, and config knobs.
- **Nested pages via `parent_id`.** Pages can now form parent → child trees. A new `path` column stores the resolved URL (cached on save), and the breadcrumb walker uses real ancestor titles instead of slug splitting. The `slug` unique index is replaced with a unique index on `path`. Migration `2026_04_27_000001_add_nesting_to_layup_pages_table.php` adds the columns and backfills `path` from `slug` for existing rows. `layup.pages.max_depth` (default `10`) caps tree depth and guards against accidental cycles.
- New **`PageTitleWidget`** (`page-title`). A page-aware heading widget that injects the current page's title at render time and reuses the heading widget's view for consistent styling. Useful for templates and shared layouts where the title varies per page.
- **Scheduled publishing.** Pages with `status = 'scheduled'` and a future `published_at` automatically flip to `published` once their publish time arrives. The `layup:publish-scheduled` console command does the work (with a `--dry-run` flag) and the service provider auto-registers it on the app's scheduler to run every minute. New `layup.scheduling.auto_publish` config (default `true`) lets hosts opt out and register the command themselves (e.g. on a single worker).
- **`layup.pages.enabled` config option** to register or hide the bundled `PageResource` in the Filament panel. Set to `false` to use Layup as a rendering engine without surfacing the admin UI; pages still render normally via the frontend controller, `@layup` directive, or `HasLayupContent` on host models. (Thanks @TheGodlyLuzer, [#20](https://github.com/Crumbls/layup/pull/20).)

### Changed
- `BaseView::render()` return type widened from `Illuminate\Contracts\View\View` to `View|Htmlable|string`. Existing Blade widgets are unaffected (returning a `View` still satisfies the wider type via covariance); the wider type lets `BaseLivewireWidget::render()` return a `string` from `Blade::render()`. Subclasses that previously declared `: View` continue to work.
- Internal type checks switched from `instanceof BaseWidget` / `is_subclass_of(..., BaseWidget::class)` to interface-based `instanceof Widget` / `is_subclass_of(..., Widget::class)` in `Crumbls\Layup\Support\Concerns\RegistersWidgets`, `Crumbls\Layup\Support\LayupContent`, `Crumbls\Layup\Testing\LayupAssertions`, and `tests/Unit/WidgetDefaultCompletenessTest.php`. Downstream code that introspects widgets at runtime should follow suit if it intends to support Livewire widgets, but no immediate change is required for code that only sees the Blade lineage.
- `Crumbls\Layup\Testing\LayupAssertions::assertWidgetRenders()` and `assertWidgetRendersWithDefaults()` now accept any `View|Htmlable|string` from `render()` (a new `renderToString()` helper coerces the result). Tests that previously called `$widget->render()->render()` directly continue to work because Blade widgets still return a `View`.
- Four built-in widgets (`HeadingWidget`, `ButtonWidget`, `NumberCounterWidget`, `NewsletterWidget`) refactored to consume identity traits. Public surface unchanged -- all static methods resolve identically. The remaining ~95 built-in widgets are untouched.
- SEO meta is now emitted on every published page. Previously the entire block was gated on `meta.description` being set, so pages without a description silently dropped all SEO meta.
- Twitter card type is now `summary_large_image` when a featured image is present, `summary` otherwise. Was hardcoded to `summary`.
- `og:type` is now `article` for pages with `published_at`, `website` otherwise. Was always implicitly `website`.
- `getMetaTitle()` honors `layup.seo.title_suffix`.
- `getFeaturedImageUrl()` falls back to `layup.seo.default_og_image` so social shares always have an image.
- Removed the always-hidden SEO `Section` from `PageResource::form()`; description editing now lives in the Page Settings modal where it is actually visible.
- Bundled reference layout (`resources/views/layouts/page.blade.php`) renders `<x-layup-seo />` instead of an undocumented `$meta` slot. The previous slot contract has been removed entirely; hosts whose layouts rendered `{{ $meta ?? '' }}` should swap it for `<x-layup-seo />` (the component is the only supported integration point).

### Fixed
- Editor-set page descriptions and Open Graph data now reach rendered HTML on host layouts. The previous slot pattern required host layouts to opt into an undocumented `meta` slot, and the bundled reference layout itself never rendered it — so vendor-publishing the layout produced zero SEO output regardless of editor input. Replacing the slot with a drop-in component eliminates the silent-failure mode.
- Backfill missing top-level defaults across 12 widgets (22 fields) so freshly inserted widgets render with complete data. Previously some fields were declared in the form schema but absent from `getDefaultData()`, producing undefined-key warnings on first render until the editor saved the widget once.
- `layup:doctor` no longer flags Repeater and Builder sub-fields as missing top-level defaults. The walker now distinguishes nested fields (whose defaults live in the parent's `default()` payload) from top-level fields, eliminating a class of false positives.
- Revision restore in the page builder produces a cleaner diff view and correctly re-applies revision content without losing widget IDs.

### Maintenance
- `bin/docs-lint` is restored and hardened against three edge cases that previously masked broken links and stale anchors during local doc builds.

## [1.2.3](https://github.com/Crumbls/layup/compare/v1.2.2...v1.2.3) (2026-04-19)

### Fixed
- `layup:install` failing with "Failed to open stream: No such file or directory" when installed from Packagist dist. The `/stubs` directory was marked `export-ignore` in `.gitattributes`, stripping the stub files (`app-layout.blade.php.stub`, `layup-widget.php.stub`, `layup-widget-view.blade.php.stub`, `layup-widget-test.php.stub`) from the distributed tarball. `InstallCommand`, `MakeWidgetCommand`, and `LayupServiceProvider::boot()` all reference these stubs at runtime, so they must ship with the package.

## [1.2.2](https://github.com/Crumbls/layup/compare/v1.2.1...v1.2.2) (2026-04-05)

### Added
- Full documentation site in `docs/` following Spatie conventions (30 pages across 6 sections)
- Getting Started guide: creating pages, adding widgets, saving and publishing
- Widget reference pages for all 5 categories (Content, Media, Interactive, Layout, Advanced)
- Configuration reference with all `config/layup.php` options
- Grid system documentation (12-column grid, responsive spans, breakpoints, visibility)
- Advanced guides: custom widgets, extending widgets, Tailwind safelist, revision history, page templates, frontend rendering, theme system, testing helpers, rendering content
- API reference: Widget contract, models, service provider, support classes
- `.gitattributes` to exclude docs, tests, and dev files from Composer dist installs
- `homepage` and `support` URLs in `composer.json` for Packagist

### Changed
- README trimmed to concise overview with links to full documentation
- Installation section updated with `layup:install` wizard and Filament prerequisite

## [1.0.6](https://github.com/Crumbls/layup/compare/v1.0.5...v1.0.6) (2026-03-10)


### Bug Fixes

* correct class name typo (dLayupContent -&gt; LayupContent) ([b2a0ba1](https://github.com/Crumbls/layup/commit/b2a0ba157374e68688389556d3a90f1cbe9e40f2))

## [1.0.5](https://github.com/Crumbls/layup/compare/1.0.4...v1.0.5) (2026-03-10)


### Bug Fixes

* **ui:** page builder with locale key ([dbd60ba](https://github.com/Crumbls/layup/commit/dbd60ba0c1b44f5163649a2308f1862860b86b99))

## [1.0.3](https://github.com/Crumbls/layup/compare/1.0.2...v1.0.3) (2026-03-08)


### Bug Fixes

* Add live-on-blur validation to widget slideOver forms ([7b89d57](https://github.com/Crumbls/layup/commit/7b89d57f7c9c63ee33612d89b9993d016e1514d2))
* Centralize FileUpload disk config for all builder forms ([9620cab](https://github.com/Crumbls/layup/commit/9620cab9032f65d32f12ce6dc3addaa7ed28db14))
* Remove hardcoded rounded-lg from slider, use Design tab border_radius ([6ae2aaa](https://github.com/Crumbls/layup/commit/6ae2aaaaf0a085c5f9d2e697f4e1804a41ffdf7a))
* Render slider rich content as unescaped HTML ([4187fbd](https://github.com/Crumbls/layup/commit/4187fbd679d4ae19a556f3df97834cfbad996598))
* Slider slides now fill parent height with absolute positioning ([7823822](https://github.com/Crumbls/layup/commit/7823822c73928fb889b1364b4941a9ad5b05a10e))

## [1.2.1](https://github.com/Crumbls/layup/compare/v1.2.0...v1.2.1) (2026-03-29)

### Added
- Global theme system with CSS custom properties (`--layup-primary`, `--layup-secondary`, `--layup-accent`, `--layup-success`, `--layup-warning`, `--layup-danger`)
- Semantic theme variables: `--layup-on-{color}` (auto-contrast), `--layup-surface`, `--layup-on-surface`, `--layup-border`, `--layup-muted` with light/dark variants
- Dark mode theme support with automatic color lightening and `->darkColors()` manual overrides
- Custom `ColorPicker` form field with theme-aware swatches and native color picker fallback
- Theme color configuration via `LayupPlugin::make()->colors()`, `->darkColors()`, `->fonts()`, `->borderRadius()`
- Filament panel color inheritance (automatic, opt out with `->withoutPanelColors()`)
- `ThemeResolver` ensures theme is hydrated on frontend routes where Filament panels don't boot
- 19 new tests for LayupTheme (dark colors, auto-lightening, CSS output) and ColorPicker field
- Mobile-responsive layouts for all 47 widget blade templates
- Theme system documentation in README with full API reference

### Changed
- All 37 widget color fields replaced with new `ColorPicker` component (swatches + custom picker)
- All hardcoded hex color defaults in widget PHP classes set to `null`; Blade views fall back to CSS variables
- All hardcoded Tailwind blue, green, red, and yellow classes replaced with theme CSS variable equivalents
- Alert, highlight-box, badge, changelog variants now derive from `--layup-success`, `--layup-warning`, `--layup-danger`
- Star ratings, checkmarks, required asterisks, success messages all use theme variables
- Cookie consent uses `--layup-on-secondary` for contrast-safe text
- Testimonial border uses inline style for overridability instead of `layup-border-primary` class
- Gradient text defaults to `--layup-primary` / `--layup-accent` instead of hardcoded purple/blue
- Grids (feature-grid, gallery, logo-grid, team-grid, metric, post-list, pricing-toggle, masonry, text-columns) collapse to 1-2 columns on mobile via scoped media query style blocks
- Flex layouts (hero buttons, blurb, step-process, icon-box, search, file-download, cookie-consent) stack vertically on mobile
- Heading sizes scale down responsively (h1: `text-2xl md:text-4xl`, h2: `text-xl md:text-3xl`, etc.)
- Padding reduced on mobile across banner, CTA, hero, slider, testimonials, flip-card, image-card, tabs, table cells
- Hotspot/image-hotspot tooltips capped to viewport width on mobile
- Lottie widget uses `max-width` + `width: 100%` instead of fixed width
- `FieldPacks::colorPair()` and `FieldPacks::hoverColors()` now use `ColorPicker` instead of `TextInput`

### Fixed
- Theme colors not loading on frontend routes (panel boot never fires outside admin)
- Info callout text unreadable when using primary color as body text (now uses `--layup-on-surface`)

- 75 built-in widgets across Content, Media, Interactive, Layout, and Advanced categories
- Flex-based 12-column grid with responsive breakpoints (sm/md/lg/xl)
- Visual span picker for click-to-set column widths per breakpoint
- Drag & drop reordering for widgets and rows
- Undo/Redo with full history stack (Ctrl+Z / Ctrl+Shift+Z)
- Searchable, categorized widget picker modal
- Three-tab form schema (Content / Design / Advanced) on every component
- Full Design tab: text color, alignment, font size, border, radius, shadow, opacity, background, padding, margin
- Responsive visibility: show/hide per breakpoint on any element
- Entrance animations: fade in, slide up/down/left/right, zoom in (via Alpine x-intersect)
- Frontend rendering with configurable routes, layouts, and SEO meta
- Tailwind safelist generation with auto-sync on page save
- Page templates: 5 built-in + save your own
- Content revisions with auto-save and configurable max
- Export/Import pages as JSON
- Widget lifecycle hooks: `onSave`, `onCreate`, `onDelete`
- Content validation (structural + widget type)
- Widget auto-discovery from `App\Layup\Widgets`
- Configurable Page model per dashboard
- Blurb icon picker with 90+ searchable Heroicons
- `make:layup-widget` Artisan command
- Pint + Rector for code quality
- Pre-push hook running Pint and Pest

### Changed
- Editor CSS restyled to match Filament's native look (flat rows, dashed columns, elevated widget cards)
- Dark mode support via Filament CSS custom properties

## [0.1.0] - 2026-02-24

### Added
- Initial development release
