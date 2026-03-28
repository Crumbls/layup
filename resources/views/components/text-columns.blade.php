@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $anim = \Crumbls\Layup\View\BaseView::animationAttributes($data);
    $columns = $data['columns'] ?? '2';
    $gap = $data['gap'] ?? '2rem';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="prose max-w-none {{ $vis }} {{ $data['class'] ?? '' }}" style="column-count: {{ $columns }}; column-gap: {{ $gap }}; {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! $anim !!}>
    {!! $data['content'] ?? '' !!}
</div>
