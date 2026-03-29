@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $shadow = !empty($data['shadow']);
    $hover = !empty($data['hover_lift']);
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="rounded-xl overflow-hidden {{ $shadow ? 'shadow-md dark:shadow-gray-900/50' : 'border dark:border-gray-700' }} {{ $hover ? 'hover:-translate-y-1 hover:shadow-lg dark:hover:shadow-gray-900/50 transition-all duration-300' : '' }} {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @if(!empty($data['image']))
        <img src="{{ asset('storage/' . $data['image']) }}" alt="{{ $data['title'] ?? '' }}" class="w-full h-48 object-cover" />
    @endif
    <div class="p-5">
        @if(!empty($data['title']))
            <h3 class="font-bold text-lg mb-2">{{ $data['title'] }}</h3>
        @endif
        @if(!empty($data['body']))
            <div class="prose prose-sm text-gray-600 dark:text-gray-300">{!! $data['body'] !!}</div>
        @endif
        @if(!empty($data['link_url']))
            <a href="{{ $data['link_url'] }}" class="inline-block mt-3 layup-text-primary hover:underline text-sm font-medium">{{ $data['link_text'] ?? __('layup::frontend.card.learn_more') }} →</a>
        @endif
    </div>
</div>
