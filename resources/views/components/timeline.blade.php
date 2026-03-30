@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $color = $data['line_color'] ?? 'var(--layup-primary)';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="relative {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <div class="absolute left-2 md:left-4 top-0 bottom-0 w-0.5" style="background-color: {{ $color }}"></div>
    @foreach(($data['events'] ?? []) as $event)
        <div class="relative pl-8 md:pl-12 pb-8 last:pb-0">
            <div class="absolute left-2.5 w-3 h-3 rounded-full border-2 bg-white dark:bg-gray-900" style="border-color: {{ $color }}; top: 0.375rem"></div>
            @if(!empty($event['date']))
                <span class="text-xs font-semibold uppercase tracking-wide" style="color: {{ $color }}">{{ $event['date'] }}</span>
            @endif
            @if(!empty($event['title']))
                <h4 class="font-semibold mt-1">{{ $event['title'] }}</h4>
            @endif
            @if(!empty($event['description']))
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $event['description'] }}</p>
            @endif
        </div>
    @endforeach
</div>
