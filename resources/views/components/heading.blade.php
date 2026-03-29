<{{ $data['level'] ?? 'h2' }}
    @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
    class="{{ match($data['level'] ?? 'h2') {
        'h1' => 'text-2xl md:text-4xl font-bold',
        'h2' => 'text-xl md:text-3xl font-bold',
        'h3' => 'text-lg md:text-2xl font-semibold',
        'h4' => 'text-lg md:text-xl font-semibold',
        'h5' => 'text-lg font-medium',
        'h6' => 'text-base font-medium',
        default => 'text-3xl font-bold',
    } }} {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }} mb-2"
    style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @if(!empty($data['link_url']))<a href="{{ $data['link_url'] }}" class="hover:underline">@endif{{ $data['content'] ?? '' }}@if(!empty($data['link_url']))</a>@endif
</{{ $data['level'] ?? 'h2' }}>
