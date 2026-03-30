@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $dir = ($data['direction'] ?? 'horizontal') === 'vertical' ? 'rotateX(180deg)' : 'rotateY(180deg)';
    $height = $data['height'] ?? '300px';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="group {{ $vis }} {{ $data['class'] ?? '' }}"
     style="perspective: 1000px; height: {{ $height }}; {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <div class="relative w-full h-full transition-transform duration-700" style="transform-style: preserve-3d; group-hover:transform: {{ $dir }}" x-data="{ flipped: false }" @mouseenter="flipped = true" @mouseleave="flipped = false" :style="flipped ? 'transform: {{ $dir }}' : ''">
        {{-- Front --}}
        <div class="absolute inset-0 rounded-xl flex flex-col items-center justify-center p-3 md:p-6 text-white dark:text-white text-center" style="backface-visibility: hidden; background-color: {{ $data['front_bg'] ?? 'var(--layup-primary)' }}">
            @if(!empty($data['front_title']))<h3 class="text-xl font-bold mb-2">{{ $data['front_title'] }}</h3>@endif
            @if(!empty($data['front_description']))<p class="text-white/80 dark:text-white/80">{{ $data['front_description'] }}</p>@endif
        </div>
        {{-- Back --}}
        <div class="absolute inset-0 rounded-xl flex flex-col items-center justify-center p-3 md:p-6 text-white dark:text-white text-center" style="backface-visibility: hidden; transform: {{ $dir }}; background-color: {{ $data['back_bg'] ?? 'var(--layup-primary)' }}">
            @if(!empty($data['back_title']))<h3 class="text-xl font-bold mb-2">{{ $data['back_title'] }}</h3>@endif
            @if(!empty($data['back_description']))<p class="text-white/80 dark:text-white/80 mb-4">{{ $data['back_description'] }}</p>@endif
            @if(!empty($data['link_url']))<a href="{{ $data['link_url'] }}" class="bg-white/20 dark:bg-white/20 hover:bg-white/30 dark:hover:bg-white/30 px-4 py-2 rounded-lg text-sm transition-colors">{{ $data['link_text'] ?? __('layup::frontend.flip_card.learn_more') }}</a>@endif
        </div>
    </div>
</div>
