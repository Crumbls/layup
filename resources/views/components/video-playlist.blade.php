@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $layout = $data['layout'] ?? 'list';
    $videos = $data['videos'] ?? [];
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     x-data="{ active: 0 }"
>
    @if($layout === 'list' && !empty($videos))
        <div class="aspect-video mb-4 rounded-lg overflow-hidden bg-black">
            @foreach($videos as $i => $video)
                @php
                    $url = $video['url'] ?? '';
                    $embedUrl = str_contains($url, 'youtube') ? 'https://www.youtube.com/embed/' . (parse_url($url, PHP_URL_QUERY) ? collect(explode('&', parse_url($url, PHP_URL_QUERY)))->mapWithKeys(fn($p) => [explode('=', $p)[0] => explode('=', $p)[1] ?? ''])->get('v', '') : basename(parse_url($url, PHP_URL_PATH))) : $url;
                @endphp
                <iframe x-show="active === {{ $i }}" src="" :src="active === {{ $i }} ? '{{ $embedUrl }}' : ''" class="w-full h-full" allowfullscreen></iframe>
            @endforeach
        </div>
        <div class="space-y-1">
            @foreach($videos as $i => $video)
                <button @click="active = {{ $i }}" class="flex items-center gap-3 w-full text-left px-3 py-2 rounded-lg transition-colors" :class="active === {{ $i }} ? '' : 'hover:bg-gray-50 dark:hover:bg-gray-800'" :style="active === {{ $i }} ? 'background-color: color-mix(in oklab, var(--layup-primary) 10%, transparent); color: var(--layup-primary);' : ''">
                    <span class="text-sm font-mono text-gray-400 dark:text-gray-500">{{ $i + 1 }}</span>
                    <span class="font-medium">{{ $video['title'] ?? '' }}</span>
                    @if(!empty($video['duration']))<span class="ml-auto text-xs text-gray-400 dark:text-gray-500">{{ $video['duration'] }}</span>@endif
                </button>
            @endforeach
        </div>
    @elseif($layout === 'grid')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($videos as $video)
                <div>
                    <div class="aspect-video rounded-lg overflow-hidden bg-black mb-2">
                        @php
                            $url = $video['url'] ?? '';
                            $embedUrl = str_contains($url, 'youtube') ? 'https://www.youtube.com/embed/' . (parse_url($url, PHP_URL_QUERY) ? collect(explode('&', parse_url($url, PHP_URL_QUERY)))->mapWithKeys(fn($p) => [explode('=', $p)[0] => explode('=', $p)[1] ?? ''])->get('v', '') : basename(parse_url($url, PHP_URL_PATH))) : $url;
                        @endphp
                        <iframe src="{{ $embedUrl }}" class="w-full h-full" allowfullscreen loading="lazy"></iframe>
                    </div>
                    <div class="font-medium text-sm">{{ $video['title'] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>
