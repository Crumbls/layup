@php
    $bg = $data['bg_color'] ?? '#1f2937';
    $pos = ($data['position'] ?? 'bottom') === 'top' ? 'top-0' : 'bottom-0';
@endphp
<div x-data="{ show: !localStorage.getItem('layup_cookie_consent') }"
     x-show="show" x-transition
     class="fixed {{ $pos }} left-0 right-0 z-50 px-4 py-4"
     style="background-color: {{ $bg }}"
>
    <div class="container mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-4">
        <p class="text-white text-sm flex-1">
            {{ $data['message'] ?? '' }}
            @if(!empty($data['policy_url']))
                <a href="{{ $data['policy_url'] }}" class="underline text-blue-300 hover:text-blue-200">{{ $data['policy_text'] ?? __('layup::frontend.cookie_consent.privacy_policy') }}</a>
            @endif
        </p>
        <div class="flex gap-2">
            @if(!empty($data['decline_text']))
                <button @click="localStorage.setItem('layup_cookie_consent', 'declined'); show = false"
                        class="text-white/70 dark:text-white/70 hover:text-white dark:hover:text-white text-sm px-4 py-2 rounded border border-white/30 dark:border-white/30">
                    {{ $data['decline_text'] }}
                </button>
            @endif
            <button @click="localStorage.setItem('layup_cookie_consent', 'accepted'); show = false"
                    class="layup-bg-primary text-white text-sm font-medium px-4 py-2 rounded layup-hover-bg-primary">
                {{ $data['accept_text'] ?? __('layup::frontend.cookie_consent.accept') }}
            </button>
        </div>
    </div>
</div>
