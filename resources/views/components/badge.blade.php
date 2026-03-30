@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $variant = $data['variant'] ?? 'default';
    $size = $data['size'] ?? 'md';
    $colors = match($variant) {
        'dark'    => 'bg-gray-800 dark:bg-gray-700 text-white',
        default   => 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100',
    };
    $badgeStyle = match($variant) {
        'success' => 'background-color: color-mix(in oklab, var(--layup-success) 15%, transparent); color: var(--layup-on-surface);',
        'warning' => 'background-color: color-mix(in oklab, var(--layup-warning) 15%, transparent); color: var(--layup-on-surface);',
        'danger'  => 'background-color: color-mix(in oklab, var(--layup-danger) 15%, transparent); color: var(--layup-on-surface);',
        'info'    => 'background-color: color-mix(in oklab, var(--layup-primary) 15%, transparent); color: var(--layup-on-surface);',
        default   => '',
    };
    $useStyleVariant = in_array($variant, ['success', 'warning', 'danger', 'info']);
    $sizeClass = match($size) {
        'sm' => 'text-xs px-2 py-0.5',
        'lg' => 'text-base px-4 py-1.5',
        default => 'text-sm px-3 py-1',
    };
    $tag = !empty($data['link_url']) ? 'a' : 'span';
@endphp
<{{ $tag }}
    @if(!empty($data['link_url']))href="{{ $data['link_url'] }}"@endif
    @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
    class="inline-block rounded-full font-medium {{ $useStyleVariant ? '' : $colors }} {{ $sizeClass }} {{ $vis }} {{ $data['class'] ?? '' }}"
    style="{{ $badgeStyle }} {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
    {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>{{ $data['text'] ?? '' }}</{{ $tag }}>
