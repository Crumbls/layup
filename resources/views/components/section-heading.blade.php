@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $align = match($data['alignment'] ?? 'center') { 'left' => 'text-left', 'right' => 'text-right', default => 'text-center' };
    $tag = $data['heading_tag'] ?? 'h2';
    $sizeClass = match($tag) { 'h1' => 'text-2xl md:text-4xl', 'h3' => 'text-lg md:text-2xl', default => 'text-xl md:text-3xl' };
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="{{ $align }} {{ $vis }} {{ $data['class'] ?? '' }}" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    <{{ $tag }} class="{{ $sizeClass }} font-bold mb-2">{{ $data['heading'] ?? '' }}</{{ $tag }}>
    @if(!empty($data['subtitle']))<p class="text-lg text-gray-500 dark:text-gray-400 mb-3">{{ $data['subtitle'] }}</p>@endif
    @if(!empty($data['show_divider']))<div class="mx-auto mt-3 {{ $data['alignment'] === 'center' ? 'mx-auto' : ($data['alignment'] === 'right' ? 'ml-auto' : '') }}" style="width: 3rem; height: 3px; background-color: {{ $data['divider_color'] ?? 'var(--layup-primary)' }}"></div>@endif
</div>
