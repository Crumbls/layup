@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $bg = $data['bg_color'] ?? 'var(--layup-primary)';
    $tc = $data['text_color_banner'] ?? 'var(--layup-on-primary)';
    $height = $data['height'] ?? 'auto';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="relative flex items-center justify-center text-center px-4 py-6 md:px-8 md:py-12 {{ $vis }} {{ $data['class'] ?? '' }}"
     style="background-color: {{ $bg }}; color: {{ $tc }}; min-height: {{ $height }}; {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @if(!empty($data['bg_image']))
        <img src="{{ asset('storage/' . $data['bg_image']) }}" alt="" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/40"></div>
    @endif
    <div class="relative z-10">
        @if(!empty($data['heading']))<h2 class="text-xl md:text-3xl font-bold mb-2">{{ $data['heading'] }}</h2>@endif
        @if(!empty($data['subtext']))<p class="text-base md:text-lg opacity-90 mb-4">{{ $data['subtext'] }}</p>@endif
        @if(!empty($data['cta_text']) && !empty($data['cta_url']))
            <a href="{{ $data['cta_url'] }}" class="inline-block bg-white dark:bg-gray-100 font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition-opacity" style="color: {{ $bg }}">{{ $data['cta_text'] }}</a>
        @endif
    </div>
</div>
