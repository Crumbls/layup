@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); $quotes = $data['quotes'] ?? []; @endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="text-center py-8 {{ $vis }} {{ $data['class'] ?? '' }}" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!} x-data="{ active: 0, total: {{ count($quotes) }} }" x-init="setInterval(() => active = (active + 1) % total, {{ ($data['interval'] ?? 5) * 1000 }})">
    @foreach($quotes as $i => $q)
        <div x-show="active === {{ $i }}" x-transition.opacity>
            <p class="text-lg md:text-2xl italic text-gray-700 dark:text-gray-200 mb-3">"{{ $q['text'] ?? '' }}"</p>
            @if(!empty($q['author']))<p class="text-sm text-gray-500 dark:text-gray-400">— {{ $q['author'] }}</p>@endif
        </div>
    @endforeach
    <div class="flex justify-center gap-2 mt-4">@foreach($quotes as $i => $q)<button @click="active = {{ $i }}" class="w-2 h-2 rounded-full transition-colors" :class="active === {{ $i }} ? 'bg-gray-800 dark:bg-white' : 'bg-gray-300 dark:bg-gray-600'"></button>@endforeach</div>
</div>
