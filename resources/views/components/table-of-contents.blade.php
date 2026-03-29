@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $levels = $data['heading_levels'] ?? ['h2', 'h3'];
    $numbered = !empty($data['numbered']);
    $collapsible = !empty($data['collapsible']);
    $sticky = !empty($data['sticky']);
    $selector = implode(',', $levels);
@endphp
<nav @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="border dark:border-gray-700 rounded-lg p-4 {{ $sticky ? 'sticky top-4' : '' }} {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     x-data="{
        open: {{ $collapsible ? 'false' : 'true' }},
        items: [],
        init() {
            document.querySelectorAll('{{ $selector }}').forEach((el, i) => {
                if (!el.id) el.id = 'toc-' + i;
                this.items.push({ id: el.id, text: el.textContent, level: el.tagName.toLowerCase() });
            });
        }
     }"
>
    @if(!empty($data['title']))
        <div class="font-bold mb-2 {{ $collapsible ? 'cursor-pointer flex justify-between items-center' : '' }}" @if($collapsible) @click="open = !open" @endif>
            {{ $data['title'] }}
            @if($collapsible) <span class="text-gray-400 dark:text-gray-500" :class="open ? 'rotate-180' : ''" style="transition: transform 0.2s">▼</span> @endif
        </div>
    @endif
    <{{ $numbered ? 'ol' : 'ul' }} x-show="open" {{ $collapsible ? 'x-collapse' : '' }} class="{{ $numbered ? 'list-decimal' : 'list-disc' }} pl-3 md:pl-5 space-y-1 text-sm">
        <template x-for="item in items" :key="item.id">
            <li :class="item.level === 'h3' ? 'ml-4' : (item.level === 'h4' ? 'ml-8' : '')">
                <a :href="'#' + item.id" x-text="item.text" class="layup-text-primary hover:underline"></a>
            </li>
        </template>
    </{{ $numbered ? 'ol' : 'ul' }}>
</nav>
