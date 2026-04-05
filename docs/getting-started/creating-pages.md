---
title: Creating Pages
weight: 1
---

## From the Filament panel

After installing Layup, a **Pages** resource appears in your Filament sidebar. Click **Create** to start a new page.

Each page has:

- **Title** -- the page name, shown in the admin list and used as the default meta title
- **Slug** -- the URL-friendly identifier (auto-generated from the title)
- **Status** -- `draft` or `published`
- **SEO fields** -- meta description and keywords
- **Content** -- the visual builder canvas

## The builder canvas

The main content area is the `LayupBuilder` field. It renders an interactive canvas where you add rows, columns, and widgets.

The canvas toolbar gives you:

- **Add Row** -- insert a new row with a column layout template (full width, 50/50, thirds, quarters, etc.)
- **Breakpoint preview** -- switch between sm, md, lg, and xl views to see responsive behavior
- **Undo/Redo** -- step through content history within the current editing session

## Adding rows

Click **Add Row** and choose a column layout from the predefined templates:

| Template | Columns |
|----------|---------|
| `[12]` | 1 full-width column |
| `[6, 6]` | 2 equal columns |
| `[4, 4, 4]` | 3 equal columns |
| `[3, 3, 3, 3]` | 4 equal columns |
| `[8, 4]` | 2/3 + 1/3 |
| `[4, 8]` | 1/3 + 2/3 |
| `[3, 6, 3]` | Sidebar + main + sidebar |
| `[2, 8, 2]` | Narrow sidebar + wide main + narrow sidebar |

Column widths use a 12-column grid. A span of `6` means the column takes up half the row.

## Row settings

Click the row gear icon to configure:

- **Direction** -- `row`, `column`, `row-reverse`, `column-reverse`
- **Justify** -- `start`, `center`, `end`, `between`, `around`, `evenly`
- **Align** -- `start`, `center`, `end`, `stretch`, `baseline`
- **Wrap** -- `nowrap`, `wrap`, `wrap-reverse`
- **Full width** -- override the container max-width

Plus the standard Design and Advanced tabs (background, padding, animation, etc.).

## Column settings

Click a column header to configure:

- **Responsive spans** -- set different column widths per breakpoint using the SpanPicker
- **Align self** -- override the row's align setting for this column
- **Overflow** -- `visible`, `hidden`, `auto`, `scroll`

## Page status

Pages start as **Draft**. Change the status to **Published** to make them visible on the frontend (if frontend routes are enabled).

You can filter the page list by status using the built-in Filament filter.
