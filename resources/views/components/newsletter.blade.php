@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $layout = $data['layout'] ?? 'inline';
    $btnColor = $data['button_color'] ?? 'var(--layup-primary)';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="text-center {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     x-data="{ submitted: false }"
>
    @if(!empty($data['heading']))
        <h3 class="text-xl font-bold mb-2">{{ $data['heading'] }}</h3>
    @endif
    @if(!empty($data['description']))
        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $data['description'] }}</p>
    @endif
    <template x-if="!submitted">
        <form action="{{ $data['action'] ?? '' }}" method="POST"
              class="{{ $layout === 'inline' ? 'flex gap-2 max-w-md mx-auto' : 'space-y-3 max-w-sm mx-auto' }}"
              @submit.prevent="fetch($el.action, { method: 'POST', body: new FormData($el) }).then(() => submitted = true).catch(() => submitted = true)">
            <input type="email" name="email" required
                   placeholder="{{ $data['placeholder'] ?? __('layup::frontend.newsletter.email_placeholder') }}"
                   class="{{ $layout === 'inline' ? 'flex-1' : 'w-full' }} border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-[var(--layup-primary)] focus:border-[var(--layup-primary)] outline-none" />
            <button type="submit"
                    class="text-white font-medium px-6 py-2.5 rounded-lg hover:opacity-90 transition-opacity {{ $layout === 'stacked' ? 'w-full' : '' }}"
                    style="background-color: {{ $btnColor }}">
                {{ $data['submit_text'] ?? __('layup::frontend.newsletter.subscribe') }}
            </button>
        </form>
    </template>
    <div x-show="submitted" x-transition class="font-semibold py-4" style="color: var(--layup-success);">
        ✓ {{ $data['success_message'] ?? __('layup::frontend.newsletter.subscribed') }}
    </div>
</div>
