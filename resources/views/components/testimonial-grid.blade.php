@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $cols = $data['columns'] ?? 3;
    $testimId = 'layup-ts-' . md5(uniqid('ts', true));
@endphp
<style>
    #{{ $testimId }} { display:grid; grid-template-columns:1fr; gap:1.5rem; }
    @media(min-width:640px) { #{{ $testimId }} { grid-template-columns:repeat(2,1fr); } }
    @media(min-width:1024px) { #{{ $testimId }} { grid-template-columns:repeat({{ $cols }},1fr); } }
</style>
<div id="{{ $testimId }}"
     @if(!empty($data['id'])) data-block-id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @foreach(($data['testimonials'] ?? []) as $t)
        <div class="border dark:border-gray-700 rounded-xl p-4 md:p-5">
            @if(!empty($t['rating']))
                <div class="mb-2" style="color: var(--layup-warning);">@for($i=0;$i<(int)$t['rating'];$i++)★@endfor</div>
            @endif
            <p class="text-gray-700 dark:text-gray-200 mb-4 italic">"{{ $t['quote'] ?? '' }}"</p>
            <div>
                <div class="font-semibold text-sm">{{ $t['name'] ?? '' }}</div>
                @if(!empty($t['role']))<div class="text-xs text-gray-500 dark:text-gray-400">{{ $t['role'] }}</div>@endif
            </div>
        </div>
    @endforeach
</div>
