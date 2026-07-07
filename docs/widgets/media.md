---
title: Media Widgets
weight: 2
nav_title: Media Widgets
order: 20
---

Media widgets handle images, video, audio, and visual presentations.

| Widget | Type | Description |
|--------|------|-------------|
| Image | `image` | Image with alt text, caption, link, and hover effects (zoom, grayscale, brightness, blur) |
| Image Card | `image-card` | Image with overlaid text content |
| Image Hotspot | `image-hotspot` | Image with clickable hotspot markers |
| Gallery | `gallery` | Image gallery with grid layout |
| Masonry | `masonry` | Masonry-style image grid |
| Slider | `slider` | Image slideshow with navigation |
| Before/After | `before-after` | Side-by-side image comparison with draggable divider |
| Video | `video` | Embedded video player (YouTube, Vimeo, or self-hosted) |
| Video Playlist | `video-playlist` | Multiple videos with playlist navigation |
| Audio | `audio` | Audio player |
| Map | `map` | Embedded map (Google Maps, OpenStreetMap) |
| Lottie | `lottie` | Lottie animation player |
| Hotspot | `hotspot` | Interactive hotspot overlay |

## Image widget details

The Image widget is the most commonly used media widget. Its content tab provides:

- **File upload** -- uses the configured upload disk (default: `public`)
- **Alt text** -- for accessibility
- **Caption** -- optional text below the image
- **Link URL** -- make the image clickable
- **Hover effect** -- `zoom`, `grayscale`, `brightness`, `blur`

The Image widget implements lifecycle hooks:

- **`onDelete`** -- deletes the uploaded file from storage when the widget is removed
- **`onDuplicate`** -- copies the file so the duplicate has its own copy

## File uploads

All media widgets that accept file uploads use the disk configured in `config/layup.php`:

```php
'uploads' => [
    'disk' => 'public',
],
```

Files are automatically stored on this disk. Make sure it is publicly accessible (run `php artisan storage:link` for the `public` disk).
