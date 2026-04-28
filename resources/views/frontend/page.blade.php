<x-dynamic-component :component="$layout">
    <x-slot:title>{{ $page->getMetaTitle() }}</x-slot:title>

    @if($page->meta['description'] ?? false)
    <x-slot:meta>
        <meta name="description" content="{{ $page->meta['description'] }}">
        <meta property="og:title" content="{{ $page->getMetaTitle() }}">
        <meta property="og:description" content="{{ $page->meta['description'] }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ $page->getUrl() }}">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="{{ $page->getMetaTitle() }}">
        <meta name="twitter:description" content="{{ $page->meta['description'] }}">
        @php($featuredImage = $page->getFeaturedImageUrl())
        @if($featuredImage)
        <meta property="og:image" content="{{ $featuredImage }}">
        <meta name="twitter:image" content="{{ $featuredImage }}">
        @endif
        <link rel="canonical" href="{{ $page->getUrl() }}">
        @foreach($page->getStructuredData() as $schema)
        <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES) !!}
        </script>
        @endforeach
    </x-slot:meta>
    @endif

    @include('layup::frontend.loop')
</x-dynamic-component>
