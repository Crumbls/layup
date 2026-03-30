<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="border border-gray-200 dark:border-gray-700 rounded-lg {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" x-data="layupTabs()" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}">
    <div class="flex border-b border-gray-200 dark:border-gray-700 overflow-x-auto" role="tablist">
        @foreach(($data['tabs'] ?? []) as $index => $tab)
            <button @click="select({{ $index }})" class="px-2 py-2 md:px-4 md:py-2.5 text-sm font-medium whitespace-nowrap transition-colors" :class="isActive({{ $index }}) ? 'border-b-2 layup-text-primary' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" :style="isActive({{ $index }}) ? 'border-color: var(--layup-primary);' : ''" role="tab">{{ $tab['title'] ?? 'Tab' }}</button>
        @endforeach
    </div>
    @foreach(($data['tabs'] ?? []) as $index => $tab)
        <div x-show="isActive({{ $index }})" class="p-4 text-gray-600 dark:text-gray-300" role="tabpanel">
            {!! $tab['content'] ?? '' !!}
        </div>
    @endforeach
</div>
