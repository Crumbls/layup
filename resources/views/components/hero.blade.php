@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $align = $data['alignment'] ?? 'center';
    $height = $data['height'] ?? 'auto';
    $overlayColor = $data['overlay_color'] ?? '#000000';
    $overlayOpacity = ($data['overlay_opacity'] ?? 50) / 100;
    $alignClass = match($align) {
        'left' => 'text-left items-start',
        'right' => 'text-right items-end',
        default => 'text-center items-center',
    };
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="relative flex flex-col justify-center {{ $alignClass }} px-4 py-8 md:px-8 md:py-16 overflow-hidden {{ $vis }} {{ $data['class'] ?? '' }}"
     style="min-height: {{ $height }}; {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @if(!empty($data['background_image']))
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $data['background_image']) }}" alt="" class="w-full h-full object-cover" />
            <div class="absolute inset-0" style="background-color: {{ $overlayColor }}; opacity: {{ $overlayOpacity }}"></div>
        </div>
    @endif
    <div class="relative z-10 max-w-3xl {{ $align === 'center' ? 'mx-auto' : '' }}">
        @if(!empty($data['heading']))
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 {{ !empty($data['background_image']) ? 'text-white' : '' }}">{{ $data['heading'] }}</h1>
        @endif
        @if(!empty($data['subheading']))
            <p class="text-xl md:text-2xl mb-4 {{ !empty($data['background_image']) ? 'text-white/80' : 'text-gray-600 dark:text-gray-300' }}">{{ $data['subheading'] }}</p>
        @endif
        @if(!empty($data['description']))
            <div class="prose mb-6 {{ !empty($data['background_image']) ? 'text-white/90' : '' }}">{!! $data['description'] !!}</div>
        @endif
        @if(!empty($data['primary_button_text']) || !empty($data['secondary_button_text']))
            <div class="flex flex-col sm:flex-row gap-4 {{ $align === 'center' ? 'justify-center' : ($align === 'right' ? 'justify-end' : '') }}">
                @if(!empty($data['primary_button_text']))
                    <a href="{{ $data['primary_button_url'] ?? '#' }}" class="layup-bg-primary text-white font-semibold px-5 py-2.5 md:px-8 md:py-3 rounded-lg layup-hover-bg-primary transition-colors">{{ $data['primary_button_text'] }}</a>
                @endif
                @if(!empty($data['secondary_button_text']))
                    <a href="{{ $data['secondary_button_url'] ?? '#' }}" class="border-2 border-current font-semibold px-5 py-2.5 md:px-8 md:py-3 rounded-lg hover:opacity-80 transition-opacity {{ !empty($data['background_image']) ? 'text-white' : '' }}">{{ $data['secondary_button_text'] }}</a>
                @endif
            </div>
        @endif
    </div>
</div>
