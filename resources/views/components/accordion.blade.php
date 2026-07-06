<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" x-data="layupAccordion({{ !empty($data['open_first']) ? 'true' : 'false' }})" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}">
    @foreach(($data['items'] ?? []) as $index => $item)
        <div>
            <button @click="toggle({{ $index }})" class="flex items-center justify-between w-full px-4 py-3 text-left font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" :class="isOpen({{ $index }}) ? 'bg-gray-50 dark:bg-gray-800' : ''">
                <span>{{ $item['title'] ?? '' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0 transition-transform duration-200" :class="isOpen({{ $index }}) ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></button>
            <div x-show="isOpen({{ $index }})" x-collapse>
                <div class="px-4 py-3 text-gray-600 dark:text-gray-300">
                    {!! $item['content'] ?? '' !!}
                </div></div></div>
    @endforeach
</div>
