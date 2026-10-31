@php
    $designData = $data;
    unset($designData['animation']);
    $animationData = $data;
    unset($animationData['responsive'], $animationData['opacity']);
    $entranceAttributes = \Crumbls\Layup\View\BaseView::animationAttributes($animationData);
@endphp
{!! \Crumbls\Layup\View\BaseView::responsiveStyles($designData) !!}
@if($entranceAttributes !== '')<div {!! $entranceAttributes !!}>@endif
@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($designData) }}"

     x-data="{ open: {{ !empty($data['start_open']) ? 'true' : 'false' }} }"
>
    <div x-show="open" x-collapse class="prose">{!! $data['content'] ?? '' !!}</div>
    <button @click="open = !open" class="layup-text-primary hover:underline text-sm font-medium mt-2" x-text="open ? '{{ $data['hide_text'] ?? __('layup::frontend.content_toggle.show_less') }}' : '{{ $data['show_text'] ?? __('layup::frontend.content_toggle.show_more') }}'"></button></div>

@if($entranceAttributes !== '')</div>@endif
