@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $testimonials = $data['testimonials'] ?? [];
    $autoplay = !empty($data['autoplay']);
    $speed = $data['speed'] ?? 5000;
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="relative {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     x-data="layupSlider({{ count($testimonials) }}, {{ $autoplay ? 'true' : 'false' }}, {{ $speed }})"
>
    @foreach($testimonials as $i => $t)
        <div x-show="isActive({{ $i }})" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="text-center px-4 py-4 md:px-8 md:py-6">
            @if(!empty($t['rating']))
                <div class="mb-3" style="color: var(--layup-warning);">@for($s = 0; $s < (int)$t['rating']; $s++)★@endfor</div>
            @endif
            <p class="text-base md:text-lg italic text-gray-700 dark:text-gray-200 mb-4 max-w-2xl mx-auto">"{{ $t['quote'] ?? '' }}"</p>
            <div class="flex items-center justify-center gap-3">
                @if(!empty($t['photo']))
                    <img src="{{ asset('storage/' . $t['photo']) }}" alt="{{ $t['author'] ?? '' }}" class="w-10 h-10 rounded-full object-cover" />
                @endif
                <div class="text-left">
                    <div class="font-semibold text-sm">{{ $t['author'] ?? '' }}</div>
                    @if(!empty($t['role']))<div class="text-xs text-gray-500 dark:text-gray-400">{{ $t['role'] }}</div>@endif
                </div>
            </div>
        </div>
    @endforeach
    @if(count($testimonials) > 1)
        <div class="flex justify-center gap-2 mt-4">
            @foreach($testimonials as $i => $t)
                <button @click="goTo({{ $i }})" :class="isActive({{ $i }}) ? 'layup-bg-primary' : 'bg-gray-300 dark:bg-gray-600'" class="w-2.5 h-2.5 rounded-full transition-colors"></button>
            @endforeach
        </div>
    @endif
</div>
