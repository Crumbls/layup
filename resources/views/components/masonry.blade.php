@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $cols = $data['columns'] ?? 3;
    $gap = $data['gap'] ?? '0.5rem';
    $rounded = !empty($data['rounded']);
    $masId = 'layup-mas-' . md5(uniqid('mas', true));
@endphp
<style>
    #{{ $masId }} { columns:1; column-gap:{{ $gap }}; }
    @media(min-width:640px) { #{{ $masId }} { columns:2; } }
    @media(min-width:1024px) { #{{ $masId }} { columns:{{ $cols }}; } }
</style>
<div id="{{ $masId }}"
     @if(!empty($data['id'])) data-block-id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @foreach(($data['images'] ?? []) as $image)
        @php
            $imgSrc = is_array($image) ? ($image['src'] ?? $image['image'] ?? $image['url'] ?? '') : $image;
            $imgAlt = is_array($image) ? ($image['alt'] ?? '') : '';
            $imgUrl = (!empty($imgSrc) && str_starts_with($imgSrc, 'http')) ? $imgSrc : (!empty($imgSrc) ? asset('storage/' . $imgSrc) : '');
        @endphp
        @if(!empty($imgSrc))
            <img src="{{ $imgUrl }}" alt="{{ $imgAlt }}" loading="lazy"
                 class="w-full mb-[{{ $gap }}] {{ $rounded ? 'rounded-lg' : '' }} hover:opacity-90 transition-opacity"
                 style="break-inside: avoid" />
        @endif
    @endforeach
</div>
