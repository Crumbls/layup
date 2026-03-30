@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
<form action="{{ $data['action'] ?? '/contact' }}" method="POST"
      @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
      class="space-y-4 {{ $vis }} {{ $data['class'] ?? '' }}"
      style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
      {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
      x-data="{ submitted: false }"
      @submit.prevent="
          fetch($el.action, { method: 'POST', body: new FormData($el) })
              .then(() => submitted = true)
              .catch(() => submitted = true);
      "
>
    @csrf
    <template x-if="!submitted">
        <div class="space-y-4">
            @foreach(($data['fields'] ?? []) as $field)
                <div>
                    <label class="block text-sm font-medium mb-1">{{ $field['label'] ?? '' }}@if(!empty($field['required'])) <span style="color: var(--layup-danger);">*</span>@endif</label>
                    @if(($field['type'] ?? 'text') === 'textarea')
                        <textarea name="{{ $field['name'] ?? '' }}" placeholder="{{ $field['placeholder'] ?? '' }}" @if(!empty($field['required']))required @endif rows="4" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-[var(--layup-primary)] focus:border-[var(--layup-primary)] outline-none"></textarea>
                    @else
                        <input type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] ?? '' }}" placeholder="{{ $field['placeholder'] ?? '' }}" @if(!empty($field['required']))required @endif class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-[var(--layup-primary)] focus:border-[var(--layup-primary)] outline-none" />
                    @endif
                </div>
            @endforeach
            <button type="submit" class="layup-bg-primary text-white font-medium px-6 py-2.5 rounded-lg layup-hover-bg-primary transition-colors">
                {{ $data['submit_text'] ?? __('layup::frontend.contact_form.send_message') }}
            </button>
        </div>
    </template>
    <div x-show="submitted" x-transition class="text-center py-8">
        <div class="text-lg font-semibold" style="color: var(--layup-success);">✓ {{ $data['success_message'] ?? __('layup::frontend.contact_form.message_sent') }}</div>
    </div>
</form>
