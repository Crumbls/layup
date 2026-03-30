@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $sizeClass = match($data['size'] ?? 'md') {
        'sm' => 'py-1.5 px-3 text-sm',
        'lg' => 'py-3 px-5 text-lg',
        default => 'py-2 px-4 text-base',
    };
@endphp
<form action="{{ $data['action'] ?? '/search' }}" method="GET"
      @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
      class="flex flex-col sm:flex-row {{ $vis }} {{ $data['class'] ?? '' }}"
      style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
      {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    <input type="search" name="{{ $data['param'] ?? 'q' }}"
           placeholder="{{ $data['placeholder'] ?? __('layup::frontend.search.placeholder') }}"
           class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-r-none sm:rounded-l-lg {{ $sizeClass }} focus:outline-none focus:ring-2 focus:ring-[var(--layup-primary)] focus:border-[var(--layup-primary)] dark:bg-gray-800 dark:text-white" />
    <button type="submit" class="layup-bg-primary text-white rounded-lg sm:rounded-l-none sm:rounded-r-lg {{ $sizeClass }} layup-hover-bg-primary transition-colors px-4">
        🔍
    </button>
</form>
