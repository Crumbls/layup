@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $cols = $data['columns'] ?? 3;
    $tgId = 'layup-tg-' . md5(uniqid('tg', true));
@endphp
<style>
    #{{ $tgId }} { display:grid; grid-template-columns:1fr; gap:1rem; }
    @media(min-width:640px) { #{{ $tgId }} { grid-template-columns:repeat(2,1fr); gap:1.5rem; } }
    @media(min-width:1024px) { #{{ $tgId }} { grid-template-columns:repeat({{ $cols }},1fr); gap:2rem; } }
</style>
<div id="{{ $tgId }}"
     @if(!empty($data['id'])) data-block-id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @foreach(($data['members'] ?? []) as $member)
        <div class="text-center">
            @php $photoSrc = $member['photo'] ?? $member['image'] ?? ''; @endphp
            @if(!empty($photoSrc))
                <img src="{{ str_starts_with($photoSrc, 'http') ? $photoSrc : asset('storage/' . $photoSrc) }}" alt="{{ $member['name'] ?? '' }}" class="w-20 h-20 md:w-24 md:h-24 rounded-full object-cover mx-auto mb-3" />
            @else
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-gray-200 dark:bg-gray-700 mx-auto mb-3 flex items-center justify-center text-gray-400 dark:text-gray-500 text-2xl">👤</div>
            @endif
            @if(!empty($member['name']))
                <div class="font-semibold">{{ $member['name'] }}</div>
            @endif
            @if(!empty($member['role']))
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $member['role'] }}</div>
            @endif
            @if(!empty($member['linkedin']) || !empty($member['twitter']))
                <div class="flex justify-center gap-2 mt-2 text-sm text-gray-400 dark:text-gray-500">
                    @if(!empty($member['linkedin']))<a href="{{ $member['linkedin'] }}" target="_blank" class="layup-hover-text-primary">{{ __('layup::frontend.team_grid.linkedin') }}</a>@endif
                    @if(!empty($member['twitter']))<a href="{{ $member['twitter'] }}" target="_blank" class="layup-hover-text-primary">{{ __('layup::frontend.team_grid.twitter') }}</a>@endif
                </div>
            @endif
        </div>
    @endforeach
</div>
