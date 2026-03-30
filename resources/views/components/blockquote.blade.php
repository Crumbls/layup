@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $style = $data['style'] ?? 'border-left';
    $color = $data['accent_color'] ?? 'var(--layup-primary)';
@endphp
<blockquote @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
    class="@if($style === 'centered') text-center @endif {{ $vis }} {{ $data['class'] ?? '' }}"
    style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }} @if($style === 'border-left')border-left: 4px solid {{ $color }}; padding-left: 1.5rem;@endif"
    {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <p class="@if($style === 'large') text-lg md:text-2xl @else text-lg @endif italic text-gray-700 dark:text-gray-200 mb-2">
        @if($style === 'large')<span style="color: {{ $color }}; font-size: 2em; line-height: 0; vertical-align: -0.3em">❝</span> @endif{{ $data['quote'] ?? '' }}
    </p>
    @if(!empty($data['attribution']) || !empty($data['source']))
        <footer class="text-sm text-gray-500 dark:text-gray-400">
            @if(!empty($data['attribution']))— {{ $data['attribution'] }}@endif
            @if(!empty($data['source'])), <cite>{{ $data['source'] }}</cite>@endif
        </footer>
    @endif
</blockquote>
