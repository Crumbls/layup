@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $cols = $data['columns'] ?? 3;
    $fgId = 'layup-fg-' . md5(uniqid('fg', true));
@endphp
<style>
    #{{ $fgId }} { display:grid; grid-template-columns:1fr; gap:1.5rem; }
    @media(min-width:640px) { #{{ $fgId }} { grid-template-columns:repeat(2,1fr); } }
    @media(min-width:1024px) { #{{ $fgId }} { grid-template-columns:repeat({{ $cols }},1fr); } }
</style>
<div id="{{ $fgId }}"
     @if(!empty($data['id'])) data-block-id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @foreach(($data['features'] ?? []) as $f)
        <div class="text-center p-4">
            <div class="text-3xl mb-3">{{ $f['emoji'] ?? '🚀' }}</div>
            <div class="font-semibold mb-1">{{ $f['title'] ?? '' }}</div>
            @if(!empty($f['description']))<div class="text-sm text-gray-600 dark:text-gray-300">{{ $f['description'] }}</div>@endif
        </div>
    @endforeach
</div>
