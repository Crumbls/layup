{!! \Crumbls\Layup\View\BaseView::responsiveStyles($data ?? []) !!}@if(!empty($data['src']))
<figure @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="{{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    @if(!empty($data['link_url']))<a href="{{ $data['link_url'] }}" @if(!empty($data['link_new_tab']))target="_blank" rel="noopener noreferrer"@endif>@endif
    @php
        $hoverClass = match($data['hover_effect'] ?? '') {
            'zoom' => 'hover:scale-105 transition-transform duration-300',
            'grayscale' => 'grayscale hover:grayscale-0 transition-all duration-300',
            'brightness' => 'hover:brightness-110 transition-all duration-300',
            'blur' => 'blur-sm hover:blur-0 transition-all duration-300',
            default => '',
        };
    @endphp
    <img src="{{ is_array($data['src']) ? '' : asset('storage/' . $data['src']) }}" alt="{{ $data['alt'] ?? '' }}" class="max-w-full h-auto {{ $hoverClass }}" />
    @if(!empty($data['link_url']))</a>@endif
    @if(!empty($data['caption']))
        <figcaption class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">{{ $data['caption'] }}</figcaption>
    @endif
</figure>
@endif
