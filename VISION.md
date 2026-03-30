# Vision

## What Layup Is

Layup is a **visual page builder for Filament** — the missing piece between "I have an admin panel" and "I can build pages without touching code."

It's not a CMS. It's not a theme. It's a plugin that gives your Filament panel a Divi-style page editor with rows, columns, and widgets — all built on native Filament form components.

## Why It Exists

It seems like every Filament project we've been on needs a way to build content pages, preferably without a developer. 
The options today are:

1. **Hardcode Blade views** — fast to start, painful to maintain
2. **Full CMS packages** — heavy, opinionated, fight your existing architecture
3. **Markdown/rich text fields** — limited layout control, no grid, no components

Layup fills the gap: structured visual editing that works *with* Filament, not against it.

## Design Principles

### Filament-Native, Not Filament-Adjacent

Layup uses Filament's form builder, not a parallel system. Widget settings are `TextInput`, `Select`, `RichEditor` — the same components you already know. No custom JavaScript form framework. No learning curve beyond Filament itself.

### Extensibility Over Completeness

75 widgets ship built-in, but the real power is making your own or overriding the blade files to match your frontend framework. 
A custom widget is one PHP class with a form schema and a Blade view. Auto-discovery means you drop it in `App\Layup\Widgets` and it shows up in the picker.

The goal isn't to ship every possible widget — it's to make building *your* widgets trivially easy.

### Progressive Complexity

Once setup, a simple page needs zero configuration. A complex multi-panel setup with custom models, separate page tables, and curated widget lists is a few lines of config. Layup scales from "just install it" to "deeply customized" without forcing the complexity upfront.

### Frontend-Agnostic

Layup handles the editor. Frontend rendering is opt-in and swappable. Use the built-in controller, or query the model and render however you want. Your Tailwind config, your layout, your styles.

## Roadmap

### Near Term
- More test coverage across all widget types
- Performance optimization for large pages (50+ widgets)
- Widget groups / reusable blocks (save a widget configuration, reuse across pages)
- Nested rows (rows inside columns for complex layouts)
- Global styles / design presets
- Copy/paste widgets between pages
- Migrate widget views from inline styles to a generated utility CSS layer -- currently many widgets apply theme colors and user overrides via inline `style` attributes. The long-term direction is a small generated stylesheet (similar to the safelist approach) that maps widget data to CSS classes, keeping markup clean and making styles cacheable, overridable, and inspectable in devtools

### Medium Term
- Visual frontend preview (live iframe in the editor)
- A/B testing support (variant pages, traffic splitting)
- Widget marketplace / community registry
- Multi-language content support
- Revision diffing (visual comparison between versions)
- API endpoint for headless rendering

### Long Term
- Collaborative editing (multi-user, live cursors)
- AI-assisted content generation (per-widget)
- Visual theme builder (edit Tailwind config from the admin)
- Page analytics integration (per-widget engagement tracking)

## How You Can Contribute

I know we will have issues as it gets used.  That's just how it goes. Submit your PRs.  If you are using an agent, ask them to review this file first to check for alignment.

### Build Widgets

The best way to contribute is to build widgets and share them. A widget package is just a Composer package with one or more `BaseWidget` subclasses. Publish it, and anyone can `composer require` it.

### Write Tests

Every widget, every edge case, every config combination. More tests means more confidence. We use Pest — if you can write a closure, you can write a test.

### Improve Documentation

Found something confusing? Fixed something that wasn't obvious? Open a PR with better docs. Real-world examples beat API references every time.

### Report Bugs

Use the issue tracker. Include: what you expected, what happened, and the smallest reproduction you can manage.

## Non-Goals

Things Layup intentionally does *not* try to be:

- **A full CMS** — No user roles, no content workflows, no publishing pipelines. Use Filament Shield or your own auth for that.
- **A frontend framework** — Layup doesn't ship CSS themes or JavaScript bundles (beyond Alpine components for interactive widgets). Your frontend, your choices.
- **A site builder** — Layup builds pages, not entire sites. Navigation, headers, footers — that's your layout.
- **A Divi clone** — The grid metaphor is borrowed, but Layup is built for Laravel developers, not drag-and-drop designers. Code-first, visual-second.

---

*Layup: structured pages for Filament, without the overhead.*
