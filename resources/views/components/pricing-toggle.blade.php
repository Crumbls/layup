@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $color = $data['accent_color'] ?? 'var(--layup-primary)';
    $plans = $data['plans'] ?? [];
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     x-data="{ annual: false }"
>
    {{-- Toggle --}}
    <div class="flex items-center justify-center gap-3 mb-8">
        <span class="text-sm font-medium" :class="!annual ? 'opacity-100' : 'opacity-50'">{{ $data['monthly_label'] ?? __('layup::frontend.pricing_toggle.monthly') }}</span>
        <button @click="annual = !annual" class="relative w-14 h-7 rounded-full transition-colors" :style="annual ? 'background-color: {{ $color }}' : 'background-color: var(--layup-border)'">
            <span class="absolute top-0.5 left-0.5 w-6 h-6 bg-white rounded-full shadow transition-transform" :class="annual ? 'translate-x-7' : ''"></span>
        </button>
        <span class="text-sm font-medium" :class="annual ? 'opacity-100' : 'opacity-50'">
            {{ $data['annual_label'] ?? __('layup::frontend.pricing_toggle.annual') }}
            @if(!empty($data['discount_badge']))
                <span class="text-xs text-white px-2 py-0.5 rounded-full ml-1" style="background-color: {{ $color }}">{{ $data['discount_badge'] }}</span>
            @endif
        </span>
    </div>

    {{-- Plans --}}
    @php
        $ptCols = min(count($plans), 4);
        $ptId = 'layup-pt-' . md5(uniqid('pt', true));
    @endphp
    <style>
        #{{ $ptId }} { display:grid; grid-template-columns:1fr; gap:1.5rem; }
        @media(min-width:640px) { #{{ $ptId }} { grid-template-columns:repeat(2,1fr); } }
        @media(min-width:1024px) { #{{ $ptId }} { grid-template-columns:repeat({{ $ptCols }},1fr); } }
    </style>
    <div id="{{ $ptId }}">
        @foreach($plans as $plan)
            <div class="border dark:border-gray-700 rounded-xl p-6 text-center {{ !empty($plan['featured']) ? 'ring-2 relative' : '' }}" @if(!empty($plan['featured'])) style="ring-color: {{ $color }}" @endif>
                @if(!empty($plan['featured']))
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 text-xs text-white px-3 py-1 rounded-full" style="background-color: {{ $color }}">{{ __('layup::frontend.pricing_table.popular') }}</span>
                @endif
                <h3 class="font-bold text-lg mb-2">{{ $plan['name'] ?? '' }}</h3>
                <div class="text-3xl font-bold mb-4">
                    <span x-show="!annual">{{ $plan['monthly_price'] ?? '' }}</span>
                    <span x-show="annual" x-cloak>{{ $plan['annual_price'] ?? '' }}</span>
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400" x-text="annual ? '{{ __('layup::frontend.pricing_toggle.per_year') }}' : '{{ __('layup::frontend.pricing_toggle.per_month') }}'"></span>
                </div>
                @if(!empty($plan['features']))
                    <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2 mb-6 text-left">
                        @foreach(explode(',', $plan['features']) as $feat)
                            <li>✓ {{ trim($feat) }}</li>
                        @endforeach
                    </ul>
                @endif
                @if(!empty($plan['cta_url']))
                    <a href="{{ $plan['cta_url'] }}" class="block text-white font-medium py-2 rounded-lg hover:opacity-90 transition-opacity" style="background-color: {{ $color }}">{{ $plan['cta_text'] ?? __('layup::frontend.pricing_table.get_started') }}</a>
                @endif
            </div>
        @endforeach
    </div>
</div>
