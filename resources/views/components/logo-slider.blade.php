@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $speed = $data['speed'] ?? 25;
    $maxH = $data['max_height'] ?? '3rem';
    $gap = $data['gap'] ?? '4rem';
    $logos = $data['logos'] ?? [];
@endphp
@if(count($logos) > 0)
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="overflow-hidden {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <div class="flex" style="animation: layup-logo-slide {{ $speed }}s linear infinite; width: max-content">
        @for($i = 0; $i < 2; $i++)
            @foreach($logos as $logo)
                @php
                    $logoSrc = is_array($logo) ? ($logo['src'] ?? $logo['image'] ?? '') : $logo;
                    $logoAlt = is_array($logo) ? ($logo['alt'] ?? $logo['name'] ?? '') : '';
                @endphp
                @if(!empty($logoSrc))
                    <img src="{{ str_starts_with($logoSrc, 'http') ? $logoSrc : asset('storage/' . $logoSrc) }}" alt="{{ $logoAlt }}" style="max-height: {{ $maxH }}; width: auto; margin-right: {{ $gap }}" class="shrink-0 opacity-70 hover:opacity-100 transition-opacity" />
                @endif
            @endforeach
        @endfor
    </div>
    <style>@keyframes layup-logo-slide { from { transform: translateX(0) } to { transform: translateX(-50%) } }</style></div>
@endif
