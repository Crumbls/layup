<x-dynamic-component :component="$layout">
    <x-slot:title>{{ $page->getMetaTitle() }}</x-slot:title>

    @include('layup::frontend.loop')
</x-dynamic-component>
