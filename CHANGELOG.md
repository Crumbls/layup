# Changelog

All notable changes to Layup will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
