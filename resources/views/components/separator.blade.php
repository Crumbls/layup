@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $style = $data['style'] ?? 'line';
    $color = $data['color'] ?? 'var(--layup-border)';
    $width = $data['width'] ?? '50%';
    $spacing = $data['spacing'] ?? '2rem';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="flex justify-center {{ $vis }} {{ $data['class'] ?? '' }}"
     style="padding: {{ $spacing }} 0; {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @switch($style)
        @case('dots')
            <div style="width: {{ $width }}; color: {{ $color }}; letter-spacing: 0.5em" class="text-center text-lg">● ● ●</div>
            @break
        @case('stars')
            <div style="width: {{ $width }}; color: {{ $color }}; letter-spacing: 0.5em" class="text-center text-lg">★ ★ ★</div>
            @break
        @case('diamond')
            <div style="width: {{ $width }}; color: {{ $color }}" class="text-center text-xl">◆</div>
            @break
        @case('wave')
            <svg viewBox="0 0 200 20" style="width: {{ $width }}; height: 20px" preserveAspectRatio="none">
                <path d="M0 10 Q25 0 50 10 T100 10 T150 10 T200 10" fill="none" stroke="{{ $color }}" stroke-width="2" />
            </svg>
            @break
        @case('fade')
            <div style="width: {{ $width }}; height: 1px; background: linear-gradient(to right, transparent, {{ $color }}, transparent)"></div>
            @break
        @default
            <div style="width: {{ $width }}; height: 1px; background-color: {{ $color }}"></div>
    @endswitch
</div>
