@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $color = $data['accent_color'] ?? 'var(--layup-primary)';
    $trendStyle = match($data['trend'] ?? '') {
        'up'      => 'color: var(--layup-success);',
        'down'    => 'color: var(--layup-danger);',
        default   => '',
    };
    $trendClass = in_array($data['trend'] ?? '', ['up', 'down']) ? '' : 'text-gray-500';
    $trendIcon = match($data['trend'] ?? '') {
        'up' => '↑',
        'down' => '↓',
        'neutral' => '→',
        default => '',
    };
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="border dark:border-gray-700 rounded-xl p-4 md:p-6 {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }} border-top: 3px solid {{ $color }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ $data['label'] ?? '' }}</div>
    <div class="text-2xl md:text-3xl font-bold" style="color: {{ $color }}">{{ $data['value'] ?? '' }}</div>
    @if(!empty($data['description']))
        <div class="text-sm mt-2 {{ $trendClass }}" style="{{ $trendStyle }}">
            {{ $trendIcon }} {{ $data['description'] }}
        </div>
    @endif
</div>
