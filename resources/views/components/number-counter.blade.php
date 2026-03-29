<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="text-center {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    <div class="text-2xl md:text-4xl font-bold mb-1">
        <span>{{ $data['prefix'] ?? '' }}</span>
        <span x-data="layupCounter({{ (int)($data['number'] ?? 0) }}, {{ ($data['animate'] ?? true) ? 'true' : 'false' }})" x-intersect.once="start()" x-text="count">0</span>
        <span>{{ $data['suffix'] ?? '' }}</span>
    </div>
    @if(!empty($data['title']))
        <div class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ $data['title'] }}</div>
    @endif
</div>
