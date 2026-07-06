@php $slideCount = count($data['slides'] ?? []); @endphp
<section
        @if(!empty($data['id'])) id="{{ $data['id'] }}" @endif
class="relative overflow-hidden {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}"
        x-data="layupSlider({{ $slideCount }}, {{ ($data['autoplay'] ?? true) ? 'true' : 'false' }}, {{ $data['speed'] ?? 5000 }})"
        style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
        role="region"
        aria-roledescription="carousel"
        aria-label="{{ $data['label'] ?? 'Image slideshow' }}"
>
    {{-- Slide track --}}
    <div
            class="relative min-h-[200px]"
            :aria-live="autoplay ? 'off' : 'polite'"
    >
        @foreach(($data['slides'] ?? []) as $index => $slide)
            <div
                    x-show="isActive({{ $index }})"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    :aria-hidden="(!isActive({{ $index }})).toString()"
                    class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800"
                    :class="isActive({{ $index }}) ? '' : 'pointer-events-none'"
                    role="group"
                    aria-roledescription="slide"
                    aria-label="Slide {{ $index + 1 }} of {{ $slideCount }}"
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
                        <a href="{{ $slide['button_url'] ?? '#' }}" class="inline-block layup-bg-primary text-white px-5 py-2.5 rounded font-medium layup-hover-bg-primary transition-colors">
                            {{ $slide['button_text'] }}
                        </a>
                    @endif
                </div></div>
        @endforeach
    </div>

    @if($slideCount > 1)
        {{-- Prev / Next --}}
        @if($data['arrows'] ?? true)
            <button
                    @click="prev()"
                    class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 dark:bg-gray-800/80 rounded-full flex items-center justify-center hover:bg-white dark:hover:bg-gray-800 transition-colors"
                    aria-label="Previous slide"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg></button>
            <button
                    @click="next()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/80 dark:bg-gray-800/80 rounded-full flex items-center justify-center hover:bg-white dark:hover:bg-gray-800 transition-colors"
                    aria-label="Next slide"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></button>
        @endif

        {{-- Pause / Play (WCAG 2.2.2) --}}
        @if($data['autoplay'] ?? true)
            <button
                    @click="autoplay = !autoplay; autoplay ? (timer = setInterval(() => next(), speed)) : clearInterval(timer)"
                    class="absolute right-2 bottom-2 w-7 h-7 bg-white/80 dark:bg-gray-800/80 rounded-full flex items-center justify-center hover:bg-white dark:hover:bg-gray-800 transition-colors"
                    :aria-label="autoplay ? 'Pause slideshow' : 'Play slideshow'"
                    :aria-pressed="(!autoplay).toString()"
            >
                <svg x-show="autoplay" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                <svg x-show="!autoplay" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg></button>
        @endif

        {{-- Dot nav --}}
        @if($data['dots'] ?? true)
            <div class="flex justify-center gap-1.5 mt-3" role="tablist" aria-label="Slides">
                @foreach(($data['slides'] ?? []) as $index => $slide)
                    <button
                            @click="goTo({{ $index }})"
                            class="w-2 h-2 rounded-full transition-colors"
                            :class="isActive({{ $index }}) ? 'layup-bg-primary' : 'bg-gray-300 dark:bg-gray-600'"
                            role="tab"
                            aria-label="Slide {{ $index + 1 }} of {{ $slideCount }}"
                            :aria-selected="isActive({{ $index }}).toString()"
                    ></button>
                @endforeach
            </div>
        @endif
    @endif
</section>
