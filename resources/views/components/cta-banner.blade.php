@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="rounded-xl px-4 py-6 md:px-8 md:py-10 flex flex-col md:flex-row items-center justify-between gap-3 md:gap-6 {{ $vis }} {{ $data['class'] ?? '' }}" style="background-color: {{ $data['bg_color'] ?? 'var(--layup-primary)' }}; color: {{ $data['text_color_banner'] ?? 'var(--layup-on-primary)' }}; {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    <div>
        <div class="text-lg md:text-2xl font-bold">{{ $data['heading'] ?? '' }}</div>
        @if(!empty($data['subtitle']))<div class="opacity-80 mt-1">{{ $data['subtitle'] }}</div>@endif
    </div>
    @if(!empty($data['button_text']))
        <a href="{{ $data['button_url'] ?? '#' }}" class="bg-white dark:bg-gray-100 text-gray-900 dark:text-gray-900 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-white transition-colors shrink-0">{{ $data['button_text'] }}</a>
    @endif
</div>
