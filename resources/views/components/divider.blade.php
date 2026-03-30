<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="flex justify-center {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" style="padding: {{ $data['spacing'] ?? '1rem' }} 0; {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    <hr class="border-0" style="width: {{ $data['width'] ?? '100%' }}; border-top: {{ $data['weight'] ?? '1px' }} {{ $data['style'] ?? 'solid' }} {{ $data['color'] ?? 'var(--layup-border)' }};" />
</div>
