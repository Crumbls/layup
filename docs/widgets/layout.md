---
title: Layout Widgets
weight: 4
---

Layout widgets control spacing, visual separation, and page navigation anchors.

| Widget | Type | Description |
|--------|------|-------------|
| Spacer | `spacer` | Vertical whitespace with configurable height |
| Divider | `divider` | Horizontal line separator |
| Separator | `separator` | Styled content separator with line variants |
| Anchor | `anchor` | Invisible anchor point for in-page navigation |

## Usage tips

**Spacer** -- use for adding vertical breathing room between content sections. Set the height through the Design tab's padding controls rather than adding empty rows.

**Divider vs Separator** -- Divider renders a simple `<hr>` element. Separator provides more styling options (line style, color, width).

**Anchor** -- renders no visible output but adds an `id` attribute to the DOM. Use with the Table of Contents widget or manual anchor links (`#section-name`).
