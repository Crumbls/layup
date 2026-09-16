---
title: Grid System
weight: 6
nav_title: Grid System
order: 60
---

Layup uses a 12-column layout with Flex (the default) and Tailwind Grid modes. Pages are built from rows containing columns, and columns contain widgets.

## Structure

```
Page
  Section (optional wrapper)
    Row (flex container)
      Column (flex item, span 1-12)
        Widget
        Widget
      Column
        Widget
    Row
      ...
```

## Rows

Each row holds one or more columns. In Row Settings, choose **Flex** or **Grid** using the Layout field. New rows default to Flex; missing, null, and unrecognized layout values also render as Flex.

Grid rows render with `grid grid-cols-12`. Their columns reuse the same responsive spans through `col-span-*`, `md:col-span-*`, `lg:col-span-*`, and `xl:col-span-*` utilities. Grid gap choices are `gap-0`, `gap-2`, `gap-4`, `gap-6`, `gap-8`, and `gap-12`; missing or unsupported gap values fall back to `gap-4`. Grid uses gaps instead of the Flex column padding gutters. Regenerate the Tailwind safelist and rebuild your frontend CSS after upgrading.

Direction, Justify, and Wrap appear only in Flex mode. Align and Full width apply to both modes. Switching modes preserves settings for the other mode. The builder canvas reflects the selected layout and Grid gap.

Row settings control flex behavior:

| Setting | Options | Default |
|---------|---------|---------|
| Direction | `row`, `column`, `row-reverse`, `column-reverse` | `row` |
| Justify | `start`, `center`, `end`, `between`, `around`, `evenly` | `start` |
| Align | `start`, `center`, `end`, `stretch`, `baseline` | `stretch` |
| Wrap | `nowrap`, `wrap`, `wrap-reverse` | `wrap` |
| Full width | toggle | `false` |

## Columns

Columns have a span from 1 to 12 in both modes. A span of 12 fills a row, 6 occupies half the tracks, and 4 occupies one-third. In Grid mode, the available width accounts for the selected gap.

### Responsive spans

Each column stores a span value per breakpoint:

```php
'span' => [
    'sm' => 12,  // Full width on mobile
    'md' => 6,   // Half width on tablet
    'lg' => 4,   // One-third on desktop
    'xl' => 4,   // One-third on large screens
]
```

The SpanPicker in the column editor lets you set these visually. If a breakpoint span is not set, the column defaults to `12` (full width).

### Column CSS

Columns use Tailwind width utility classes at each breakpoint:

```
sm:w-full    (span 12)
md:w-6/12    (span 6)
lg:w-4/12    (span 4)
xl:w-4/12    (span 4)
```

These classes are included in the auto-generated safelist.

### Column settings

Beyond span, columns support:

- **Align self** -- override the row's alignment for this column (`auto`, `start`, `center`, `end`, `stretch`, `baseline`)
- **Overflow** -- content overflow behavior (`visible`, `hidden`, `auto`, `scroll`)
- All standard Design tab fields (padding, margin, background, border, etc.)
- All standard Advanced tab fields (ID, classes, animation, visibility)

## Breakpoints

Four breakpoints are configured by default:

| Key | Label | Width | Icon |
|-----|-------|-------|------|
| `sm` | Mobile | 640px | Phone |
| `md` | Tablet | 768px | Tablet |
| `lg` | Desktop | 1024px | Desktop |
| `xl` | Large | 1280px | TV |

The builder toolbar lets you switch between breakpoints to preview how the layout responds. The `default_breakpoint` config option (default: `lg`) controls which view is shown when you first open the editor.

## Responsive visibility

Any component (row, column, or widget) can be hidden at specific breakpoints using the **Hide on** checkboxes in the Advanced tab. This generates Tailwind visibility classes:

```php
// Hide on mobile, show on tablet and up
BaseView::visibilityClasses(['sm']);
// Returns: "hidden md:block"

// Hide on tablet only
BaseView::visibilityClasses(['md']);
// Returns: "md:hidden lg:block"
```

## Row templates

When adding a new row, you choose from predefined column layouts. The default templates are:

- `[12]` -- single full-width column
- `[6, 6]` -- two equal halves
- `[4, 4, 4]` -- three equal thirds
- `[3, 3, 3, 3]` -- four equal quarters
- `[8, 4]` -- two-thirds plus one-third
- `[4, 8]` -- one-third plus two-thirds
- `[3, 6, 3]` -- sidebar, main, sidebar
- `[2, 8, 2]` -- narrow sidebar, wide main, narrow sidebar

Add custom templates in the `row_templates` config array. Values are column spans that should sum to 12.
