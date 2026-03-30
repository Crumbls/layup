<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="text-center {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" x-data="layupCountdown('{{ $data['target_date'] ?? '' }}')" x-init="start()" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}">
    @if(!empty($data['title']))
        <div class="text-lg font-semibold mb-4">{{ $data['title'] }}</div>
    @endif
    <div class="flex justify-center gap-2 md:gap-4" x-show="!expired">
        @if($data['show_days'] ?? true)
            <div class="flex flex-col items-center">
                <span class="text-2xl md:text-3xl font-bold" x-text="days">0</span>
                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('layup::frontend.countdown.days') }}</span>
            </div>
        @endif
        @if($data['show_hours'] ?? true)
            <div class="flex flex-col items-center">
                <span class="text-2xl md:text-3xl font-bold" x-text="hours">0</span>
                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('layup::frontend.countdown.hours') }}</span>
            </div>
        @endif
        @if($data['show_minutes'] ?? true)
            <div class="flex flex-col items-center">
                <span class="text-2xl md:text-3xl font-bold" x-text="minutes">0</span>
                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('layup::frontend.countdown.min') }}</span>
            </div>
        @endif
        @if($data['show_seconds'] ?? true)
            <div class="flex flex-col items-center">
                <span class="text-2xl md:text-3xl font-bold" x-text="seconds">0</span>
                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('layup::frontend.countdown.sec') }}</span>
            </div>
        @endif
    </div>
    <div x-show="expired" class="text-xl font-semibold" style="color: var(--layup-success);">
        {{ $data['expired_message'] ?? __('layup::frontend.countdown.time_is_up') }}
    </div>
</div>
