@php
    $bg = $data['bg_color'] ?? 'var(--layup-secondary)';
    $pos = ($data['position'] ?? 'bottom') === 'top' ? 'top-0' : 'bottom-0';
@endphp
<div x-data="{ show: !localStorage.getItem('layup_cookie_consent') }"
     x-show="show" x-transition
     class="fixed {{ $pos }} left-0 right-0 z-50 px-4 py-4"
     style="background-color: {{ $bg }}"
>
    <div class="container mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-4">
        <p class="text-sm flex-1" style="color: var(--layup-on-secondary);">
            {{ $data['message'] ?? '' }}
            @if(!empty($data['policy_url']))
                <a href="{{ $data['policy_url'] }}" class="underline" style="color: var(--layup-primary);">{{ $data['policy_text'] ?? __('layup::frontend.cookie_consent.privacy_policy') }}</a>
            @endif
        </p>
        <div class="flex gap-2">
            @if(!empty($data['decline_text']))
                <button @click="localStorage.setItem('layup_cookie_consent', 'declined'); show = false"
                        class="text-sm px-4 py-2 rounded" style="color: var(--layup-on-secondary); opacity: 0.7; border: 1px solid color-mix(in oklab, var(--layup-on-secondary) 30%, transparent);">
                    {{ $data['decline_text'] }}
                </button>
            @endif
            <button @click="localStorage.setItem('layup_cookie_consent', 'accepted'); show = false"
                    class="text-sm font-medium px-4 py-2 rounded" style="background-color: var(--layup-primary); color: var(--layup-on-primary);">
                {{ $data['accept_text'] ?? __('layup::frontend.cookie_consent.accept') }}
            </button>
        </div>
    </div>
</div>
