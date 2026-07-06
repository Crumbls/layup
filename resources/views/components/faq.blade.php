@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $style = $data['style'] ?? 'accordion';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="space-y-3 {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @foreach(($data['items'] ?? []) as $i => $item)
        @if($style === 'accordion')
            <div x-data="{ open: false }" class="border dark:border-gray-700 rounded-lg">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-left font-medium">
                    <span>{{ $item['question'] ?? '' }}</span>
                    <span x-text="open ? '−' : '+'" class="text-lg"></span></button>
                <div x-show="open" x-collapse class="px-4 pb-3 text-gray-600 dark:text-gray-300">{{ $item['answer'] ?? '' }}</div></div>
        @elseif($style === 'cards')
            <div class="border dark:border-gray-700 rounded-lg p-4">
                <div class="font-semibold mb-2">{{ $item['question'] ?? '' }}</div>
                <div class="text-gray-600 dark:text-gray-300">{{ $item['answer'] ?? '' }}</div></div>
        @else
            <div class="pb-3 @if(!$loop->last) border-b dark:border-gray-700 @endif">
                <div class="font-semibold mb-1">{{ $item['question'] ?? '' }}</div>
                <div class="text-gray-600 dark:text-gray-300">{{ $item['answer'] ?? '' }}</div></div>
        @endif
    @endforeach
</div>
