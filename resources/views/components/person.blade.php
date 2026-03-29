<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="text-center {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    @if(!empty($data['photo']))
        <img src="{{ asset('storage/' . $data['photo']) }}" alt="{{ $data['name'] ?? '' }}" class="w-20 h-20 md:w-24 md:h-24 rounded-full object-cover mx-auto mb-4" />
    @endif
    @if(!empty($data['name']))
        <h3 class="text-lg font-semibold">{{ $data['name'] }}</h3>
    @endif
    @if(!empty($data['role']))
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $data['role'] }}</p>
    @endif
    @if(!empty($data['bio']))
        <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">{{ $data['bio'] }}</p>
    @endif
    @php $socials = array_filter([$data['email'] ?? '', $data['website'] ?? '', $data['twitter'] ?? '', $data['linkedin'] ?? '', $data['facebook'] ?? '']); @endphp
    @if(count($socials))
        <div class="flex justify-center gap-3 text-sm">
            @if(!empty($data['email']))<a href="mailto:{{ $data['email'] }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">{{ __('layup::frontend.person.email') }}</a>@endif
            @if(!empty($data['website']))<a href="{{ $data['website'] }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" target="_blank" rel="noopener">{{ __('layup::frontend.person.web') }}</a>@endif
            @if(!empty($data['twitter']))<a href="{{ $data['twitter'] }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" target="_blank" rel="noopener">{{ __('layup::frontend.person.twitter') }}</a>@endif
            @if(!empty($data['linkedin']))<a href="{{ $data['linkedin'] }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" target="_blank" rel="noopener">{{ __('layup::frontend.person.linkedin') }}</a>@endif
            @if(!empty($data['facebook']))<a href="{{ $data['facebook'] }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" target="_blank" rel="noopener">{{ __('layup::frontend.person.facebook') }}</a>@endif
        </div>
    @endif
</div>
