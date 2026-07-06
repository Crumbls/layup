@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $sep = $data['separator'] ?? '/';
    $items = $data['items'] ?? [];
@endphp
<nav @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="text-sm {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     aria-label="Breadcrumb"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <ol class="flex flex-wrap items-center gap-1 list-none p-0 m-0">
        @foreach($items as $i => $item)
            @if($i > 0)
                <li class="text-gray-400 dark:text-gray-500 mx-1" aria-hidden="true">{{ $sep }}</li>
            @endif
            <li>
                @if(!empty($item['url']) && $i < count($items) - 1)
                    <a href="{{ $item['url'] }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">{{ $item['label'] ?? '' }}</a>
                @else
                    <span class="text-gray-700 dark:text-gray-200 font-medium" aria-current="page">{{ $item['label'] ?? '' }}</span>
                @endif
            </li>
        @endforeach
    </ol></nav>
