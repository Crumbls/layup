@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $type = $data['type'] ?? 'info';
    $typeStyle = match($type) {
        'success' => 'background-color: color-mix(in oklab, var(--layup-success) 10%, transparent); border-color: var(--layup-success); color: var(--layup-on-surface);',
        'warning' => 'background-color: color-mix(in oklab, var(--layup-warning) 10%, transparent); border-color: var(--layup-warning); color: var(--layup-on-surface);',
        'danger'  => 'background-color: color-mix(in oklab, var(--layup-danger) 10%, transparent); border-color: var(--layup-danger); color: var(--layup-on-surface);',
        default   => 'background-color: color-mix(in oklab, var(--layup-primary) 10%, transparent); border-color: var(--layup-primary); color: var(--layup-on-surface);',
    };
    $icon = match($type) {
        'success' => '✓',
        'warning' => '⚠',
        'danger'  => '✕',
        default   => 'ℹ',
    };
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="border-l-4 p-4 rounded-r {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ $typeStyle }} {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     @if(!empty($data['dismissible'])) x-data="{ show: true }" x-show="show" x-transition @endif
>
    <div class="flex items-start gap-3">
        <span class="text-lg font-bold shrink-0">{{ $icon }}</span>
        <div class="flex-1">
            @if(!empty($data['title']))
                <div class="font-semibold mb-1">{{ $data['title'] }}</div>
            @endif
            @if(!empty($data['content']))
                <div class="text-sm">{!! $data['content'] !!}</div>
            @endif
        </div>
        @if(!empty($data['dismissible']))
            <button @click="show = false" class="text-current opacity-50 hover:opacity-100 shrink-0">&times;</button>
        @endif
    </div>
</div>
