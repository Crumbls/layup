@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $testimonials = $data['testimonials'] ?? [];
    $speed = (int)($data['autoplay_speed'] ?? 5000);
@endphp
@if(count($testimonials) > 0)
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     x-data="{
        current: 0,
        total: {{ count($testimonials) }},
        @if($speed > 0) interval: null, @endif
        init() {
            @if($speed > 0)
            this.interval = setInterval(() => this.next(), {{ $speed }});
            @endif
        },
        next() { this.current = (this.current + 1) % this.total },
        prev() { this.current = (this.current - 1 + this.total) % this.total },
     }"
>
    <div class="relative overflow-hidden">
        @foreach($testimonials as $i => $t)
            <div x-show="current === {{ $i }}" x-transition.opacity class="text-center px-4 py-4 md:px-8 md:py-6">
                @if(!empty($t['rating']))
                    <div class="text-xl mb-3" style="color: var(--layup-warning);">@for($s = 0; $s < (int)$t['rating']; $s++)★@endfor</div>
                @endif
                <blockquote class="text-base md:text-lg italic text-gray-700 dark:text-gray-200 mb-4 max-w-2xl mx-auto">"{{ $t['quote'] ?? '' }}"</blockquote>
                <div class="flex items-center justify-center gap-3">
                    @if(!empty($t['avatar']))
                        <img src="{{ asset('storage/' . $t['avatar']) }}" alt="" class="w-12 h-12 rounded-full object-cover" />
                    @endif
                    <div class="text-left">
                        <div class="font-semibold">{{ $t['name'] ?? '' }}</div>
                        @if(!empty($t['title']))<div class="text-sm text-gray-500 dark:text-gray-400">{{ $t['title'] }}</div>@endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if(count($testimonials) > 1)
        <div class="flex justify-center gap-2 mt-4">
            <button @click="prev()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 text-xl">‹</button>
            @foreach($testimonials as $i => $t)
                <button @click="current = {{ $i }}" class="w-2 h-2 rounded-full transition-colors" :class="current === {{ $i }} ? '' : 'bg-gray-300 dark:bg-gray-600'" :style="current === {{ $i }} ? 'background-color: var(--layup-primary);' : ''"></button>
            @endforeach
            <button @click="next()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 text-xl">›</button>
        </div>
    @endif
</div>
@endif
