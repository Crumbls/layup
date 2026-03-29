<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="relative overflow-hidden {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" x-data="layupSlider({{ count($data['slides'] ?? []) }}, {{ ($data['autoplay'] ?? true) ? 'true' : 'false' }}, {{ $data['speed'] ?? 5000 }})" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}">
    <div class="relative min-h-[200px]">
        @foreach(($data['slides'] ?? []) as $index => $slide)
            <div
                x-show="isActive({{ $index }})"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800"
                :class="isActive({{ $index }}) ? '' : 'pointer-events-none'"
                @if(!empty($slide['image']))
                    style="background-image:url('{{ asset('storage/' . $slide['image']) }}');background-size:cover;background-position:center"
                @endif
            >
                <div class="max-w-2xl mx-auto text-center p-4 md:p-8">
                    @if(!empty($slide['heading']))
                        <h3 class="text-2xl font-bold mb-2">{{ $slide['heading'] }}</h3>
                    @endif
                    @if(!empty($slide['content']))
                        <div class="prose dark:prose-invert mb-4">{!! $slide['content'] !!}</div>
                    @endif
                    @if(!empty($slide['button_text']))
                        <a href="{{ $slide['button_url'] ?? '#' }}" class="inline-block layup-bg-primary text-white px-5 py-2.5 rounded font-medium layup-hover-bg-primary transition-colors">{{ $slide['button_text'] }}</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @if(($data['arrows'] ?? true) && count($data['slides'] ?? []) > 1)
        <button @click="prev()" class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 dark:bg-gray-800/80 rounded-full flex items-center justify-center hover:bg-white dark:hover:bg-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        </button>
        <button @click="next()" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 dark:bg-gray-800/80 rounded-full flex items-center justify-center hover:bg-white dark:hover:bg-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </button>
    @endif
    @if(($data['dots'] ?? true) && count($data['slides'] ?? []) > 1)
        <div class="flex justify-center gap-1.5 mt-3">
            @foreach(($data['slides'] ?? []) as $index => $slide)
                <button @click="goTo({{ $index }})" class="w-2 h-2 rounded-full transition-colors" :class="isActive({{ $index }}) ? 'layup-bg-primary' : 'bg-gray-300 dark:bg-gray-600'"></button>
            @endforeach
        </div>
    @endif
</div>
