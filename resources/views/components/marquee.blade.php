@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $speed = $data['speed'] ?? 20;
    $direction = ($data['direction'] ?? 'left') === 'right' ? 'reverse' : 'normal';
    $pause = !empty($data['pause_on_hover']);
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="overflow-hidden whitespace-nowrap {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <div class="inline-block" style="animation: layup-marquee {{ $speed }}s linear infinite {{ $direction }}; @if($pause) &:hover { animation-play-state: paused } @endif">
        <span class="inline-block px-4">{{ $data['text'] ?? '' }}</span>
        <span class="inline-block px-4">{{ $data['text'] ?? '' }}</span></div>
    <style>@keyframes layup-marquee { from { transform: translateX(0) } to { transform: translateX(-50%) } }</style></div>
