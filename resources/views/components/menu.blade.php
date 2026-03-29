@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $orientation = ($data['orientation'] ?? 'horizontal') === 'vertical' ? 'flex-col' : 'flex-row flex-wrap gap-y-2';
    $style = $data['style'] ?? 'links';
    $itemClass = match($style) {
        'pills' => 'px-4 py-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors',
        'underline' => 'pb-1 border-b-2 border-transparent hover:border-current transition-colors',
        default => 'layup-hover-text-primary transition-colors',
    };
@endphp
<nav @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <ul class="flex {{ $orientation }} gap-4 list-none p-0 m-0">
        @foreach(($data['items'] ?? []) as $item)
            @if(!empty($item['label']) && !empty($item['url']))
                <li>
                    <a href="{{ $item['url'] }}" class="{{ $itemClass }}" @if(!empty($item['new_tab']))target="_blank" rel="noopener noreferrer"@endif>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</nav>
