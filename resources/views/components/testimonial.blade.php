<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="border-l-4 pl-4 md:pl-6 py-2{{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" style="border-color: var(--layup-primary); {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    @if(!empty($data['rating']))
        <div class="mb-2" style="color: var(--layup-warning);">@for($i = 0; $i < (int)$data['rating']; $i++)★@endfor</div>
    @endif
    @if(!empty($data['quote']))
        <blockquote class="text-base md:text-lg italic text-gray-700 dark:text-gray-200 mb-4">"{{ $data['quote'] }}"</blockquote>
    @endif
    <div class="flex items-center gap-3">
        @if(!empty($data['photo']))
            <img src="{{ asset('storage/' . $data['photo']) }}" alt="{{ $data['author'] ?? '' }}" class="w-10 h-10 rounded-full object-cover" />
        @endif
        <div>
            @if(!empty($data['author']))
                <p class="font-semibold text-sm">
                    @if(!empty($data['url']))<a href="{{ $data['url'] }}" class="hover:underline">@endif
                    {{ $data['author'] }}
                    @if(!empty($data['url']))</a>@endif
                </p>
            @endif
            @if(!empty($data['role']) || !empty($data['company']))
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $data['role'] ?? '' }}@if(!empty($data['role']) && !empty($data['company'])), @endif{{ $data['company'] ?? '' }}</p>
            @endif
        </div>
    </div>
</div>
