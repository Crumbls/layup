@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $v = $data['variant'] ?? 'info';
    $variantStyle = match($v) {
        'tip'       => 'background-color: color-mix(in oklab, var(--layup-success) 10%, transparent); border-color: var(--layup-success); color: var(--layup-on-surface);',
        'warning'   => 'background-color: color-mix(in oklab, var(--layup-warning) 10%, transparent); border-color: var(--layup-warning); color: var(--layup-on-surface);',
        'important' => 'background-color: color-mix(in oklab, var(--layup-danger) 10%, transparent); border-color: var(--layup-danger); color: var(--layup-on-surface);',
        'note'      => '',
        default     => 'background-color: color-mix(in oklab, var(--layup-primary) 10%, transparent); border-color: color-mix(in oklab, var(--layup-primary) 40%, transparent); color: var(--layup-on-surface);',
    };
    $noteClass = $v === 'note' ? 'bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200' : '';
    $icons = match($v) {
        'tip' => '💚', 'warning' => '⚠️', 'important' => '❗', 'note' => '📝', default => '💡',
    };
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="border-l-4 rounded-r-lg p-4 {{ $noteClass }} {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ $variantStyle }} {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <div class="flex gap-2 items-start">
        <span class="text-lg">{{ $data['icon'] ?? $icons }}</span>
        <div>
            @if(!empty($data['title']))<div class="font-semibold mb-1">{{ $data['title'] }}</div>@endif
            @if(!empty($data['content']))<div class="prose prose-sm">{!! $data['content'] !!}</div>@endif
        </div>
    </div>
</div>
