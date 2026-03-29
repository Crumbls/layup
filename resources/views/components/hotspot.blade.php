@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
@if(!empty($data['image']))
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="relative inline-block {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <img src="{{ asset('storage/' . $data['image']) }}" alt="" class="w-full h-auto" />
    @foreach(($data['points'] ?? []) as $i => $point)
        <div class="absolute" style="left: {{ $point['x'] ?? 50 }}%; top: {{ $point['y'] ?? 50 }}%; transform: translate(-50%, -50%)"
             x-data="{ show: false }">
            <button @click="show = !show" class="w-6 h-6 layup-bg-primary rounded-full border-2 border-white dark:border-gray-300 shadow-lg text-white text-xs flex items-center justify-center hover:scale-110 transition-transform cursor-pointer">{{ $i + 1 }}</button>
            <div x-show="show" x-transition @click.away="show = false" class="absolute z-10 bottom-full left-1/2 -translate-x-1/2 mb-2 bg-white dark:bg-gray-800 rounded-lg shadow-xl dark:shadow-gray-900/50 p-3 min-w-0 sm:min-w-48 max-w-[calc(100vw-2rem)] text-sm">
                <div class="font-semibold">{{ $point['label'] ?? '' }}</div>
                @if(!empty($point['description']))<div class="text-gray-500 dark:text-gray-400 mt-1">{{ $point['description'] }}</div>@endif
            </div>
        </div>
    @endforeach
</div>
@endif
