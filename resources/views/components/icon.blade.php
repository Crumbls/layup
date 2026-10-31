{!! \Crumbls\Layup\View\BaseView::responsiveStyles($data ?? []) !!}<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="inline-flex {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    @if(!empty($data['url']))<a href="{{ $data['url'] }}">@endif
    @if(!empty($data['icon']))
        <x-dynamic-component :component="$data['icon']" style="width:{{ $data['size'] ?? '2.5rem' }};height:{{ $data['size'] ?? '2.5rem' }};{{ !empty($data['color']) ? 'color:'.$data['color'] : '' }}" />
    @endif
    @if(!empty($data['url']))</a>@endif
</div>
