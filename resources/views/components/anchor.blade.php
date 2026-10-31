{!! \Crumbls\Layup\View\BaseView::responsiveStyles($data ?? []) !!}
@if(!empty($data['anchor_id']))
<div id="{{ !empty($data['id']) ? $data['id'] : $data['anchor_id'] }}"
     class="{{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ empty($data['invisible']) ? ($data['class'] ?? '') : '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }} @if(($data['offset'] ?? 0) != 0) scroll-margin-top: {{ abs((int) ($data['offset'] ?? 0)) }}px; @endif"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
></div>
@endif
