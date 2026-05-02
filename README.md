# Layup

A Filament form field that turns any JSON column into a visual content editor. Drop `LayupBuilder::make('content')` into any Filament form and your editors get a Divi-style canvas with rows, columns, and 96 extensible widgets. Layup ships with a Pages resource as a turnkey example, but the field is the product -- pair it with the `HasLayupContent` trait on any Eloquent model.

**[Documentation](https://crumbls.com/documentation/layup)** -- full documentation available online.

**[Live Sandbox](https://sandbox.crumbls.com)** -- try the editor without installing anything.

![Layup Showcase](layup-showcase.jpg)

## Features

- **Flex-based 12-column grid** with responsive breakpoints (sm/md/lg/xl)
- **Visual span picker** -- click-to-set column widths per breakpoint
- **Drag & drop** -- reorder widgets and rows
- **Undo/Redo** -- Ctrl+Z / Ctrl+Shift+Z with full history stack
- **Widget picker modal** -- searchable, categorized, grid layout
- **Three-tab form schema** -- Content / Design / Advanced on every component
- **Full Design tab** -- text color, alignment, font size, border, border radius, box shadow, opacity, background color, padding, margin
- **Responsive visibility** -- show/hide per breakpoint on any element
- **Entrance animations** -- fade in, slide up/down/left/right, zoom in (via Alpine x-intersect)
- **Frontend rendering** -- configurable routes, layouts, and SEO meta (OG, Twitter Cards, canonical, JSON-LD, breadcrumbs, robots, article timestamps)
- **Tailwind safelist** -- automatic class collection for dynamic content
- **Page templates** -- 5 built-in templates (blank, landing, about, contact, pricing) + save your own
- **Content revisions** -- auto-save on content change, configurable max, restore from history
- **Export / Import** -- pages as JSON files
- **Widget lifecycle hooks** -- `onSave`, `onCreate`, `onDelete`, `onDuplicate` with optional context
- **Content validation** -- structural + widget type validation
- **Form Field Packs** -- reusable field groups for image, link, and color patterns
- **`prepareForRender()` hook** -- transform widget data before rendering
- **Widget validation rules** -- self-declaring validation via `getValidationRules()`
- **Widget deprecation** -- graceful sunset path with `isDeprecated()`
- **`WidgetData` value object** -- typed accessors for Blade views
- **Widget debug command** -- `layup:debug-widget` for instant widget introspection
- **Render isolation** -- broken widgets no longer crash entire pages
- **Widget search tags** -- additional terms for the builder's widget picker
- **Widget asset declaration** -- declare JS/CSS dependencies per widget
- **Widget auto-discovery** -- scans `App\Layup\Widgets` for custom widgets
- **Global theme system** -- CSS custom properties for colors, fonts, and border radius with Filament panel inheritance
- **Dark mode theme support** -- automatic color lightening with manual overrides
- **Configurable model** -- swap the Page model per dashboard
- **`HasLayupContent` trait** -- add Layup rendering to any Eloquent model
- **`<x-layup-widget>` component** -- render individual widgets in any Blade template
- **Testing helpers** -- factory states and assertions for custom widget development
- **Developer tooling** -- `layup:doctor`, `layup:list-widgets`, `layup:search` commands
- **Publishable stubs** -- customize `make-widget` scaffolding templates
- **Dual-render widgets** -- Blade (default) or Livewire per widget, opt-in via `BaseLivewireWidget`
- **Nested pages** -- parent → child page trees with breadcrumb-aware URL paths
- **Scheduled publishing** -- pages flip from `scheduled` to `published` automatically at their `published_at` time
- **1,168 tests, 3,649 assertions**

### Built-in Widgets (96)

| Category | Widgets |
|----------|---------|
| **Content** (58) | Text, Heading, Page Title, Rich Text, Blurb, Icon, Icon Box, Icon List, Badge, Card, Alert, List, Blockquote, Banner, Section Heading, Accordion, Toggle, Tabs, Feature List, Feature Grid, Testimonial, Testimonial Carousel, Testimonial Grid, Testimonial Slider, Breadcrumbs, Person, Step Process, Team Grid, Logo Grid, Logo Slider, Avatar Group, Price, Metric, Social Proof, Image Text, Text Columns, Timeline, Animated Heading, Bar Counter, Highlight Box, Number Counter, Star Rating, Gradient Text, Typewriter, Quote Carousel, Marquee, Table of Contents, Stat Card, Changelog, Menu, Notification Bar, Table, Comparison Table, Skill Bar, Progress Circle, Post List, Hero, FAQ (with JSON-LD) |
| **Media** (13) | Image (with hover effects), Gallery (with lightbox + captions), Video, Video Playlist, Audio, Slider, Masonry, Lottie, Map, Before/After, Image Card, Hotspot, Image Hotspot |
| **Interactive** (18) | Button (hover colors), Call to Action, CTA Banner, Countdown, Pricing Table, Pricing Toggle, Social Follow, Search, Contact Form, Login, Newsletter, Modal, Flip Card, Cookie Consent, Content Toggle, Share Buttons, File Download, Back to Top |
| **Layout** (4) | Spacer, Divider, Separator, Anchor |
| **Advanced** (3) | HTML, Code Block, Embed |

## Requirements

- PHP 8.3+
- Laravel 12+
- Filament 5
- Livewire 4

## Documentation & Installation

Full documentation is available at **[crumbls.com/documentation/layup](https://crumbls.com/documentation/layup)**.

- [Getting Started](https://crumbls.com/documentation/layup/getting-started) -- creating pages, adding widgets, publishing
- [Widgets](https://crumbls.com/documentation/layup/widgets) -- all 95 built-in widgets by category
- [Configuration](https://crumbls.com/documentation/layup/configuration) -- every config option
- [Grid System](https://crumbls.com/documentation/layup/grid-system) -- 12-column grid, breakpoints, responsive behavior
- [Advanced](https://crumbls.com/documentation/layup/advanced) -- custom widgets, theme system, frontend rendering, testing, templates, revisions, Tailwind safelist
- [API Reference](https://crumbls.com/documentation/layup/api-reference) -- Widget contract, models, service provider, support classes

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for development setup, code style, and testing guidelines. For widget development, see [AGENTS.md](AGENTS.md) for a detailed reference.

## Vision & Roadmap

See [VISION.md](VISION.md) for where Layup is headed and how you can help shape it.

## License

MIT -- see [LICENSE.md](LICENSE.md)