@php
    $bg = $data['bg_color'] ?? 'var(--layup-primary)';
    $tc = $data['text_color_btn'] ?? 'var(--layup-on-primary)';
    $pos = ($data['position'] ?? 'right') === 'left' ? 'left: 1.5rem' : 'right: 1.5rem';
    $sizeClass = match($data['size'] ?? 'md') {
        'sm' => 'w-10 h-10 text-sm',
        'lg' => 'w-14 h-14 text-xl',
        default => 'w-12 h-12 text-base',
    };
    $showAfter = $data['show_after'] ?? 300;
@endphp
<div x-data="{ visible: false }"
     @scroll.window="visible = window.scrollY > {{ $showAfter }}"
     class="fixed bottom-6 z-50"
     style="{{ $pos }}"
>
    <button x-show="visible" x-transition
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="{{ $sizeClass }} rounded-full shadow-lg flex items-center justify-center hover:opacity-90 transition-opacity"
            style="background-color: {{ $bg }}; color: {{ $tc }}"
            aria-label="{{ __('layup::frontend.back_to_top.label') }}">
        ↑
    </button>
</div>
