@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $lightbox = !empty($data['lightbox']);
    $showCaptions = !empty($data['show_captions']);
    $captions = $showCaptions ? array_map('trim', explode("\n", $data['captions_text'] ?? '')) : [];
    $galCols = $data['columns'] ?? 3;
    $galGap = $data['gap'] ?? '0.5rem';
    $galId = 'layup-gal-' . md5(uniqid('gal', true));
@endphp
<style>
    #{{ $galId }} { display:grid; grid-template-columns:1fr; gap:{{ $galGap }}; }
    @media(min-width:640px) { #{{ $galId }} { grid-template-columns:repeat(2,1fr); } }
    @media(min-width:1024px) { #{{ $galId }} { grid-template-columns:repeat({{ $galCols }},1fr); } }
</style>
<div id="{{ $galId }}"
     @if(!empty($data['id'])) data-block-id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     @if($lightbox) x-data="layupLightbox()" @endif
>
    @foreach(($data['images'] ?? []) as $idx => $image)
        @php
            $imgSrc = is_array($image) ? ($image['src'] ?? $image['image'] ?? $image['url'] ?? '') : $image;
            $imgAlt = is_array($image) ? ($image['alt'] ?? $image['caption'] ?? '') : ($captions[$idx] ?? '');
            $imgUrl = str_starts_with($imgSrc, 'http') ? $imgSrc : asset('storage/' . $imgSrc);
        @endphp
        @if(!empty($imgSrc))
            <div class="overflow-hidden rounded">
                @if($lightbox)
                    <img src="{{ $imgUrl }}" alt="{{ $imgAlt }}" loading="lazy"
                         class="w-full h-auto block hover:scale-105 transition-transform duration-300 cursor-pointer"
                         data-lightbox-src="{{ $imgUrl }}"
                         @click="show('{{ $imgUrl }}')" />
                @else
                    <img src="{{ $imgUrl }}" alt="{{ $imgAlt }}" loading="lazy"
                         class="w-full h-auto block hover:scale-105 transition-transform duration-300" />
                @endif
                @if($showCaptions && !empty($captions[$idx]))
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 px-1">{{ $captions[$idx] }}</p>
                @endif
            </div>
        @endif
    @endforeach

    @if($lightbox)
        <template x-teleport="body">
            <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/90" @click.self="close()" @keydown.escape.window="close()" @keydown.arrow-right.window="next()" @keydown.arrow-left.window="prev()">
                <button @click="close()" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300">&times;</button>
                <button @click="prev()" class="absolute left-4 text-white text-3xl hover:text-gray-300">&#8249;</button>
                <img :src="current" class="max-w-[90vw] max-h-[90vh] object-contain" />
                <button @click="next()" class="absolute right-4 text-white text-3xl hover:text-gray-300">&#8250;</button>
            </div>
        </template>
    @endif
</div>
