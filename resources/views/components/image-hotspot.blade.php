@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $pinColor = $data['pin_color'] ?? 'var(--layup-danger)';
@endphp
@if(!empty($data['image']))
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="relative inline-block {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <img src="{{ asset('storage/' . $data['image']) }}" alt="" class="w-full h-auto block rounded" />
    @foreach(($data['hotspots'] ?? []) as $spot)
        <div class="absolute" style="left: {{ $spot['x'] ?? 50 }}%; top: {{ $spot['y'] ?? 50 }}%; transform: translate(-50%, -50%)" x-data="{ open: false }">
            <button @click="open = !open" @click.outside="open = false"
                    class="w-6 h-6 rounded-full border-2 border-white dark:border-gray-300 shadow-lg cursor-pointer animate-pulse hover:animate-none hover:scale-125 transition-transform"
                    style="background-color: {{ $pinColor }}">
            </button>
            <div x-show="open" x-transition class="absolute z-10 mt-2 left-1/2 -translate-x-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-xl dark:shadow-gray-900/50 p-3 min-w-0 sm:min-w-48 max-w-[calc(100vw-2rem)] text-sm">
                @if(!empty($spot['title']))<div class="font-semibold mb-1">{{ $spot['title'] }}</div>@endif
                @if(!empty($spot['description']))<div class="text-gray-600 dark:text-gray-300 text-xs">{{ $spot['description'] }}</div>@endif
                @if(!empty($spot['link_url']))<a href="{{ $spot['link_url'] }}" class="layup-text-primary hover:underline text-xs mt-1 inline-block">{{ __('layup::frontend.image_hotspot.learn_more') }} →</a>@endif
            </div>
        </div>
    @endforeach
</div>
@endif
