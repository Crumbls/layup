@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $steps = $data['steps'] ?? [];
    $layout = $data['layout'] ?? 'horizontal';
    $color = $data['accent_color'] ?? 'var(--layup-primary)';
    $isHorizontal = $layout === 'horizontal';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <div class="{{ $isHorizontal ? 'flex flex-col md:flex-row items-start' : 'space-y-6' }}">
        @foreach($steps as $i => $step)
            <div class="{{ $isHorizontal ? 'flex-1 text-center relative' : 'flex gap-4' }}">
                {{-- Number circle --}}
                <div class="{{ $isHorizontal ? 'mx-auto' : '' }} w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0"
                     style="background-color: {{ $color }}">
                    {{ $i + 1 }}
                </div>
                {{-- Connector line (horizontal) --}}
                @if($isHorizontal && !$loop->last)
                    <div class="hidden md:block absolute top-5 left-[calc(50%+20px)] right-[calc(-50%+20px)] h-0.5" style="background-color: {{ $color }}; opacity: 0.3"></div>
                @endif
                <div class="{{ $isHorizontal ? 'mt-3' : '' }}">
                    @if(!empty($step['title']))
                        <div class="font-semibold">{{ $step['title'] }}</div>
                    @endif
                    @if(!empty($step['description']))
                        <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $step['description'] }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
