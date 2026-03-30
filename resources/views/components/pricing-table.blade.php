<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="border rounded-xl p-4 md:p-6{{ !empty($data['featured']) ? 'layup-border-primary ring-2 ring-[var(--layup-primary)] relative' : 'border-gray-200 dark:border-gray-700' }} {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    @if(!empty($data['featured']))
        <div class="absolute -top-3 left-1/2 -translate-x-1/2 layup-bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $data['badge_text'] ?? __('layup::frontend.pricing_table.popular') }}</div>
    @endif
    @if(!empty($data['title']))
        <h3 class="text-lg font-bold text-center mb-1">{{ $data['title'] }}</h3>
    @endif
    @if(!empty($data['subtitle']))
        <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-4">{{ $data['subtitle'] }}</p>
    @endif
    @if(!empty($data['price']))
        <div class="text-center mb-6">
            <span class="text-sm text-gray-500 dark:text-gray-400 align-top">{{ $data['currency'] ?? '$' }}</span>
            <span class="text-2xl md:text-4xl font-bold">{{ $data['price'] }}</span>
            @if(!empty($data['period']))
                <span class="text-sm text-gray-500 dark:text-gray-400">/{{ $data['period_custom'] ?? $data['period'] }}</span>
            @endif
        </div>
    @endif
    @if(!empty($data['features']))
        <ul class="space-y-2 mb-6">
            @foreach($data['features'] as $feature)
                <li class="flex items-center gap-2 text-sm">
                    @if($feature['included'] ?? true)
                        <svg class="w-4 h-4 shrink-0" style="color: var(--layup-success);" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    @else
                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span class="text-gray-400 dark:text-gray-500">
                    @endif
                    {{ $feature['text'] ?? '' }}
                    @if(!($feature['included'] ?? true))</span>@endif
                </li>
            @endforeach
        </ul>
    @endif
    @if(!empty($data['button_text']))
        <a href="{{ $data['button_url'] ?? '#' }}" class="block text-center w-full py-2.5 rounded font-medium transition-colors {{ !empty($data['featured']) ? 'layup-bg-primary text-white layup-hover-bg-primary' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
            {{ $data['button_text'] }}
        </a>
    @endif
</div>
