@php
    $layout = ($data['layout'] ?? null) === 'grid' ? 'grid' : 'flex';
    $gap = in_array($data['gap'] ?? null, \Crumbls\Layup\View\Row::GAPS, true) ? $data['gap'] : 'gap-4';
    $layoutClasses = $layout === 'grid' ? 'grid grid-cols-12 ' . $gap : 'flex flex-wrap';
    $fullWidth = !empty($data['full_width']);
    $maxWidth = \Crumbls\Layup\Support\PageLayout::resolve($page ?? null);
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
@endphp
<div
    @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
    class="{{ $vis }} {{ $data['class'] ?? '' }}"
    style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
    {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <div class="{{ $layoutClasses }} {{ $fullWidth ? '' : $maxWidth . ' mx-auto' }}"
        style="
            @if($layout === 'flex')
                flex-direction: {{ in_array($data['direction'] ?? null, ['row', 'column', 'row-reverse', 'column-reverse'], true) ? $data['direction'] : 'row' }};
                flex-wrap: {{ in_array($data['wrap'] ?? null, ['nowrap', 'wrap', 'wrap-reverse'], true) ? $data['wrap'] : 'wrap' }};
            @if(!empty($data['justify']) && $data['justify'] !== 'start')justify-content: {{ match($data['justify']) { 'center' => 'center', 'end' => 'flex-end', 'between' => 'space-between', 'around' => 'space-around', 'evenly' => 'space-evenly', default => 'flex-start' } }};@endif
            @endif
            @if(!empty($data['align']) && $data['align'] !== 'stretch')align-items: {{ match($data['align']) { 'start' => 'flex-start', 'end' => 'flex-end', 'center' => 'center', 'baseline' => 'baseline', default => 'stretch' } }};@endif
        "
    >
        @foreach($children as $child)
            @php
                $child->setPosition(
                    first: $loop->first,
                    last: $loop->last,
                );
            @endphp
            {!! $child->render()->with('layout', $layout) !!}
        @endforeach
    </div>
</div>
