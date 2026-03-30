<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="space-y-3 {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}">
    @foreach(($data['bars'] ?? []) as $bar)
        <div x-data="layupBarCounter({{ (int)($bar['percent'] ?? 0) }}, {{ ($data['animate'] ?? true) ? 'true' : 'false' }})" x-intersect.once="start()">
            <div class="flex justify-between text-sm mb-1">
                <span class="font-medium">{{ $bar['label'] ?? '' }}</span>
                @if($data['show_percent'] ?? true)
                    <span class="text-gray-500 dark:text-gray-400">{{ $bar['percent'] ?? 0 }}%</span>
                @endif
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-1000 ease-out"
                     :style="'width: ' + width + '%; background-color: {{ $bar['color'] ?? 'var(--layup-primary)' }}'"
                ></div>
            </div>
        </div>
    @endforeach
</div>
