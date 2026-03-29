@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="flex flex-col sm:flex-row items-center gap-4 border dark:border-gray-700 rounded-lg p-4 {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <div class="text-3xl">📄</div>
    <div class="flex-1">
        <div class="font-semibold">{{ $data['title'] ?? '' }}</div>
        @if(!empty($data['description']))<div class="text-sm text-gray-500 dark:text-gray-400">{{ $data['description'] }}</div>@endif
        @if(!empty($data['file_size']))<div class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $data['file_size'] }}</div>@endif
    </div>
    @if(!empty($data['file']))
        <a href="{{ asset('storage/' . $data['file']) }}" download class="layup-bg-primary text-white text-sm font-medium px-4 py-2 rounded-lg layup-hover-bg-primary transition-colors">{{ $data['button_text'] ?? __('layup::frontend.file_download.download') }}</a>
    @endif
</div>
