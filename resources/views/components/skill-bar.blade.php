@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="space-y-3 {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @foreach(($data['skills'] ?? []) as $skill)
        <div>
            <div class="flex justify-between text-sm mb-1">
                <span class="font-medium">{{ $skill['name'] ?? '' }}</span>
                <span class="text-gray-500 dark:text-gray-400">{{ $skill['percent'] ?? 0 }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden"
                 x-data="{ width: 0 }"
                 x-intersect.once="setTimeout(() => width = {{ $skill['percent'] ?? 0 }}, 100)"
            >
                <div class="h-full rounded-full transition-all duration-1000 ease-out"
                     :style="'width: ' + width + '%; background-color: {{ $skill['color'] ?? 'var(--layup-primary)' }}'"></div>
            </div>
        </div>
    @endforeach
</div>
