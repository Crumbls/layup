@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
@php $tag = !empty($data['link_url']) ? 'a' : 'div'; @endphp
<{{ $tag }}
    @if(!empty($data['link_url']))href="{{ $data['link_url'] }}" target="_blank"@endif
    @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
    class="inline-flex items-center gap-3 bg-gray-50 dark:bg-gray-800 rounded-full px-5 py-2.5 {{ $vis }} {{ $data['class'] ?? '' }}"
    style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
    {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <span class="text-lg" style="color: var(--layup-warning);">★</span>
    <span class="font-bold">{{ $data['rating'] ?? '4.9' }}</span>
    @if(!empty($data['badge_text']))<span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background-color: color-mix(in oklab, var(--layup-success) 15%, transparent); color: var(--layup-on-surface);">{{ $data['badge_text'] }}</span>@endif
    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $data['review_count'] ?? '' }} reviews on {{ $data['platform'] ?? '' }}</span>
</{{ $tag }}>
