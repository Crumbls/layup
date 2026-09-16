@php
    $id = !empty($data['id']) ? $data['id'] : ($data['anchor_id'] ?? '');
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
@endphp
@if(!empty($data['anchor_id']))
<div
    id="{{ $id }}"
    class="{{ $vis }} {{ $data['class'] ?? '' }}"
    style="@if(($data['offset'] ?? 0) != 0)scroll-margin-top: {{ abs((int)$data['offset']) }}px; @endif{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
    {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
></div>
@endif
