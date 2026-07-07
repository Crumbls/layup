---
title: Adding Widgets
weight: 2
nav_title: Adding Widgets
order: 20
---

## The widget picker

Inside any column, click the **+** button to open the widget picker. Widgets are grouped into five categories:

- **Content** -- text, headings, cards, testimonials, FAQs, timelines, etc.
- **Media** -- images, galleries, video, audio, sliders, maps
- **Interactive** -- buttons, CTAs, forms, modals, countdowns, pricing tables
- **Layout** -- spacers, dividers, separators, anchors
- **Advanced** -- raw HTML, code blocks, embeds

Use the search box to filter by name or keyword.

## Editing a widget

Click any widget on the canvas to open its editor slideover. Every widget has three tabs:

### Content tab

Widget-specific fields. For example, a Heading widget shows:

- Heading text
- Level (h1 through h6)
- Optional link URL

### Design tab

Shared across all widgets:

- **Text color** -- color picker
- **Text alignment** -- left, center, right, justify
- **Font size** -- xs through 5xl
- **Border** -- radius, width, style, color
- **Box shadow** -- xs through 2xl
- **Opacity** -- 10% through 90%
- **Padding** -- top, right, bottom, left (SpacingPicker)
- **Margin** -- top, right, bottom, left (SpacingPicker)
- **Background color** -- color picker

### Advanced tab

Shared across all widgets:

- **ID** -- HTML `id` attribute for anchor links
- **CSS classes** -- additional Tailwind or custom classes
- **Inline CSS** -- raw CSS declarations
- **Hide on** -- check breakpoints to hide (sm, md, lg, xl)
- **Entrance animation** -- fade-in, slide-up, slide-down, slide-left, slide-right, zoom-in
- **Animation duration** -- 300ms (fast), 500ms (normal), 700ms (slow), 1000ms (very slow)

## Widget actions

Each widget on the canvas supports:

- **Edit** -- open the settings slideover
- **Duplicate** -- create a copy in the same column
- **Delete** -- remove from the column
- **Drag** -- reorder within or between columns

## Previewing

Widget content shows a short text preview directly on the builder canvas. For example:

- Text widgets show the first 60 characters
- Heading widgets show `[H2] Your heading text`
- Image widgets show the filename
- Buttons show the label text
