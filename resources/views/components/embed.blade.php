@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
@if(!empty($data['html']))
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="overflow-hidden {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }} @if(!empty($data['aspect']))aspect-ratio: {{ $data['aspect'] }};@endif @if(!empty($data['max_width']))max-width: {{ $data['max_width'] }};@endif"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @if(config('layup.allow_raw_html', true))
        {!! $data['html'] !!}
    @else
        {{ $data['html'] }}
    @endif
</div>
@endif
