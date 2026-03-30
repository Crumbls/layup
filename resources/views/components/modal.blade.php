@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $maxW = match($data['size'] ?? 'md') {
        'sm' => '400px',
        'lg' => '800px',
        'xl' => '1000px',
        default => '600px',
    };
    $btnBg = $data['trigger_bg_color'] ?? 'var(--layup-primary)';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     x-data="{ open: false }"
>
    <button @click="open = true"
            class="text-white font-medium px-6 py-2.5 rounded-lg hover:opacity-90 transition-opacity"
            style="background-color: {{ $btnBg }}">
        {{ $data['trigger_text'] ?? __('layup::frontend.modal.open') }}
    </button>

    <template x-teleport="body">
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="open = false" @keydown.escape.window="open && (open = false)">
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70"></div>
            <div class="relative bg-white dark:bg-gray-900 rounded-xl shadow-2xl dark:shadow-gray-900/50 w-full p-6 overflow-y-auto max-h-[90vh]" style="max-width: {{ $maxW }}" x-transition>
                <button @click="open = false" class="absolute top-3 right-3 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 text-xl">&times;</button>
                @if(!empty($data['title']))
                    <h2 class="text-xl font-bold mb-4">{{ $data['title'] }}</h2>
                @endif
                @if(!empty($data['body']))
                    <div class="prose">{!! $data['body'] !!}</div>
                @endif
            </div>
        </div>
    </template>
</div>
