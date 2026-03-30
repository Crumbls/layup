@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $align = $data['alignment'] ?? 'top';
    $isTop = $align === 'top';
    $iconBg = $data['icon_bg'] ?? 'color-mix(in oklab, var(--layup-primary) 10%, var(--layup-surface))';
    $iconColor = $data['icon_color'] ?? 'var(--layup-primary)';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $isTop ? 'text-center' : 'flex flex-col md:flex-row gap-4' }} {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <div class="{{ $isTop ? 'mx-auto mb-3' : 'shrink-0' }} w-14 h-14 rounded-xl flex items-center justify-center text-2xl" style="background-color: {{ $iconBg }}; color: {{ $iconColor }}">
        {{ $data['icon'] ?? '⚡' }}
    </div>
    <div>
        @if(!empty($data['title']))
            @if(!empty($data['link_url']))
                <a href="{{ $data['link_url'] }}" class="font-bold text-lg layup-hover-text-primary">{{ $data['title'] }}</a>
            @else
                <h3 class="font-bold text-lg">{{ $data['title'] }}</h3>
            @endif
        @endif
        @if(!empty($data['description']))
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $data['description'] }}</p>
        @endif
    </div>
</div>
