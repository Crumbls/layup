---
title: Getting Started
weight: 3
---

This section walks through the basics of building pages with Layup: creating pages in the Filament panel, adding widgets with the visual builder, configuring rows and columns, and publishing pages to the frontend.

## Prerequisites

Before starting, make sure you have:

1. Installed the package and registered `LayupPlugin` in your panel provider (see [Installation](/docs/installation))
2. Run migrations (`php artisan migrate`)
3. Set up the Tailwind safelist (see [Installation](/docs/installation#tailwind-safelist-setup))


## Quick start

1. Visit your Filament panel (e.g., `/admin`)
2. Click **Pages** in the sidebar
3. Click **Create** to start a new page
4. Add a row by clicking **Add Row** and choosing a column layout
5. Click the **+** button inside a column to add a widget
6. Edit the widget in the slideover (Content, Design, Advanced tabs)
7. Set the status to **Published** and save

Your page is now live at `/{prefix}/{slug}` (default: `/pages/{slug}`).
