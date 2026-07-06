@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $pos = $data['initial_position'] ?? 50;
@endphp
@if(!empty($data['before_image']) && !empty($data['after_image']))
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="relative overflow-hidden select-none {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     x-data="{ pos: {{ $pos }}, dragging: false }"
     @mousedown="dragging = true"
     @mouseup.window="dragging = false"
     @mousemove.window="if (dragging) { const r = $el.getBoundingClientRect(); pos = Math.max(0, Math.min(100, ((event.clientX - r.left) / r.width) * 100)); }"
     @touchmove="const r = $el.getBoundingClientRect(); pos = Math.max(0, Math.min(100, ((event.touches[0].clientX - r.left) / r.width) * 100));"
>
    <img src="{{ asset('storage/' . $data['after_image']) }}" alt="{{ $data['after_label'] ?? __('layup::frontend.before_after.after') }}" class="w-full h-auto block" />
    <div class="absolute inset-0 overflow-hidden" :style="'width: ' + pos + '%'">
        <img src="{{ asset('storage/' . $data['before_image']) }}" alt="{{ $data['before_label'] ?? __('layup::frontend.before_after.before') }}" class="h-full object-cover object-left" style="width: 100vw; max-width: none" /></div>
    <div class="absolute top-0 bottom-0 w-1 bg-white dark:bg-gray-300 shadow cursor-ew-resize" :style="'left: ' + pos + '%'">
        <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-8 h-8 bg-white dark:bg-gray-300 rounded-full shadow-lg flex items-center justify-center text-gray-600 dark:text-gray-900 text-xs font-bold">↔</div></div>
    <span class="absolute top-2 left-2 bg-black/60 text-white text-xs px-2 py-1 rounded">{{ $data['before_label'] ?? __('layup::frontend.before_after.before') }}</span>
    <span class="absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded">{{ $data['after_label'] ?? __('layup::frontend.before_after.after') }}</span></div>
@endif
