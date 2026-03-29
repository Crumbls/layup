@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $anim = \Crumbls\Layup\View\BaseView::animationAttributes($data);
    $columns = $data['columns'] ?? '2';
    $gap = $data['gap'] ?? '2rem';
    $tcId = 'layup-tc-' . md5(uniqid('tc', true));
@endphp
<style>
    #{{ $tcId }} { column-count:1; column-gap:{{ $gap }}; }
    @media(min-width:768px) { #{{ $tcId }} { column-count:{{ $columns }}; } }
</style>
<div id="{{ $tcId }}"
     @if(!empty($data['id'])) data-block-id="{{ $data['id'] }}"@endif
     class="prose max-w-none {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! $anim !!}>
    {!! $data['content'] ?? '' !!}
</div>
