---
title: Advanced Widgets
weight: 5
nav_title: Advanced Widgets
order: 50
---

Advanced widgets provide low-level control for developers who need to embed raw code or third-party content.

| Widget | Type | Description |
|--------|------|-------------|
| HTML | `html` | Raw HTML content -- renders exactly as entered |
| Code | `code` | Syntax-highlighted code block for documentation |
| Embed | `embed` | Third-party embed (iframe, script tags, oEmbed URLs) |

## HTML widget

The HTML widget accepts arbitrary HTML and renders it without escaping. Use it for:

- Custom markup not covered by other widgets
- Inline SVGs
- Third-party snippet code

Content editors should use this sparingly -- prefer structured widgets when possible.

## Code widget

Displays code with syntax highlighting. Useful for documentation pages, developer-facing content, or tutorial sites.

## Embed widget

Accepts embed codes or URLs for third-party content (YouTube, Vimeo, Twitter, CodePen, etc.). Handles iframe and script-based embeds.
