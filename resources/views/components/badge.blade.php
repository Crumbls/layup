@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $variant = $data['variant'] ?? 'default';
    $size = $data['size'] ?? 'md';
    $colors = match($variant) {
        'success' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200',
        'warning' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200',
        'danger' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200',
        'info' => '',
        'dark' => 'bg-gray-800 dark:bg-gray-700 text-white',
        default => 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100',
    };
    $infoStyle = $variant === 'info'
        ? 'background-color: color-mix(in oklab, var(--layup-primary) 15%, transparent); color: var(--layup-on-surface);'
        : '';
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
    class="inline-block rounded-full font-medium {{ $colors }} {{ $sizeClass }} {{ $vis }} {{ $data['class'] ?? '' }}"
    style="{{ $infoStyle }} {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
    {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>{{ $data['text'] ?? '' }}</{{ $tag }}>
