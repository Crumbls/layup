@php
    $description = $page->getMetaDescription();
    $featuredImage = $page->getFeaturedImageUrl();
    $publishedAt = $page->published_at;
    $isArticle = (bool) $publishedAt;
    $ogType = $isArticle ? 'article' : 'website';
    $twitterCard = $featuredImage ? 'summary_large_image' : 'summary';
    $locale = str_replace('-', '_', app()->getLocale());
    $noindex = (bool) ($page->meta['noindex'] ?? false);
    $siteName = config('layup.seo.site_name') ?: config('app.name');
@endphp

@if($noindex)
<meta name="robots" content="noindex,nofollow">
@endif

@if($description)
<meta name="description" content="{{ $description }}">
@endif

<meta property="og:title" content="{{ $page->getMetaTitle() }}">
@if($description)
<meta property="og:description" content="{{ $description }}">
@endif
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:url" content="{{ $page->getUrl() }}">
<meta property="og:locale" content="{{ $locale }}">
@if($siteName)
<meta property="og:site_name" content="{{ $siteName }}">
@endif
@if($featuredImage)
<meta property="og:image" content="{{ $featuredImage }}">
@endif

<meta name="twitter:card" content="{{ $twitterCard }}">
<meta name="twitter:title" content="{{ $page->getMetaTitle() }}">
@if($description)
<meta name="twitter:description" content="{{ $description }}">
@endif
@if($featuredImage)
<meta name="twitter:image" content="{{ $featuredImage }}">
@endif

@if($isArticle)
<meta property="article:published_time" content="{{ $publishedAt->toIso8601String() }}">
@if($page->updated_at)
<meta property="article:modified_time" content="{{ $page->updated_at->toIso8601String() }}">
@endif
@endif

<link rel="canonical" href="{{ $page->getUrl() }}">

@foreach($page->getStructuredData() as $schema)
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES) !!}
</script>
@endforeach
