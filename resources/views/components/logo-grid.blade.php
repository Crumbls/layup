@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $cols = $data['columns'] ?? 4;
    $maxH = $data['max_height'] ?? '3rem';
    $gray = !empty($data['grayscale']);
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @if(!empty($data['title']))
        <p class="text-center text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-6">{{ $data['title'] }}</p>
    @endif
    @php $lgId = 'layup-lg-' . md5(uniqid('lg', true)); @endphp
    <style>
        #{{ $lgId }} { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; align-items:center; justify-items:center; }
        @media(min-width:640px) { #{{ $lgId }} { grid-template-columns:repeat(3,1fr); gap:1.5rem; } }
        @media(min-width:1024px) { #{{ $lgId }} { grid-template-columns:repeat({{ $cols }},1fr); gap:2rem; } }
    </style>
    <div id="{{ $lgId }}">
        @foreach(($data['logos'] ?? []) as $logo)
            @php
                $logoSrc = is_array($logo) ? ($logo['src'] ?? $logo['image'] ?? '') : $logo;
                $logoAlt = is_array($logo) ? ($logo['alt'] ?? $logo['name'] ?? '') : '';
            @endphp
            @if(!empty($logoSrc))
                <img src="{{ str_starts_with($logoSrc, 'http') ? $logoSrc : asset('storage/' . $logoSrc) }}" alt="{{ $logoAlt }}" style="max-height:{{ $maxH }};width:auto" class="{{ $gray ? 'grayscale hover:grayscale-0 opacity-60 hover:opacity-100' : '' }} transition-all duration-300" />
            @endif
        @endforeach
    </div>
</div>
