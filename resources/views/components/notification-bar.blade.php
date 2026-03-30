@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $bg = $data['bg_color'] ?? 'var(--layup-primary)';
    $textColor = $data['text_color_bar'] ?? 'var(--layup-on-primary)';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="py-3 px-4 text-center text-sm {{ $vis }} {{ $data['class'] ?? '' }}"
     style="background-color: {{ $bg }}; color: {{ $textColor }}; {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     @if(!empty($data['dismissible'])) x-data="{ show: true }" x-show="show" x-transition @endif
>
    <div class="flex items-center justify-center gap-3">
        <span>{{ $data['text'] ?? '' }}</span>
        @if(!empty($data['link_text']) && !empty($data['link_url']))
            <a href="{{ $data['link_url'] }}" class="underline font-semibold hover:opacity-80" style="color: {{ $textColor }}">{{ $data['link_text'] }}</a>
        @endif
        @if(!empty($data['dismissible']))
            <button @click="show = false" class="ml-2 opacity-60 hover:opacity-100" style="color: {{ $textColor }}">&times;</button>
        @endif
    </div>
</div>
