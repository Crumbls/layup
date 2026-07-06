@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
<ul @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="space-y-3 {{ $vis }} {{ $data['class'] ?? '' }}" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    @foreach(($data['items'] ?? []) as $item)
        <li class="flex gap-3 items-start">
            @if(!empty($item['icon']))
                <span class="text-lg shrink-0">{{ $item['icon'] }}</span>
            @endif
            <div>
                <div class="font-medium">{{ $item['text'] ?? '' }}</div>
                @if(!empty($item['description']))<div class="text-sm text-gray-500 dark:text-gray-400">{{ $item['description'] }}</div>@endif
            </div></li>
    @endforeach
</ul>
