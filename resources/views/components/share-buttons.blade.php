@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $networks = $data['networks'] ?? ['facebook', 'twitter', 'linkedin', 'email'];
    $style = $data['style'] ?? 'icon';
    $layout = ($data['layout'] ?? 'horizontal') === 'vertical' ? 'flex-col' : 'flex-row';
    $newTab = !empty($data['new_tab']);
    $target = $newTab ? '_blank' : '_self';
    $url = urlencode(request()->url());
    $title = urlencode(request()->get('title', ''));

    $shareUrls = [
        'facebook' => ['url' => "https://www.facebook.com/sharer/sharer.php?u={$url}", 'icon' => 'f', 'label' => 'Facebook', 'bg' => '#1877f2'],
        'twitter' => ['url' => "https://twitter.com/intent/tweet?url={$url}&text={$title}", 'icon' => '𝕏', 'label' => 'X', 'bg' => '#000'],
        'linkedin' => ['url' => "https://www.linkedin.com/sharing/share-offsite/?url={$url}", 'icon' => 'in', 'label' => 'LinkedIn', 'bg' => '#0077b5'],
        'reddit' => ['url' => "https://reddit.com/submit?url={$url}&title={$title}", 'icon' => 'r/', 'label' => 'Reddit', 'bg' => '#ff4500'],
        'email' => ['url' => "mailto:?subject={$title}&body={$url}", 'icon' => '', 'label' => __('layup::frontend.person.email'), 'bg' => '#6b7280'],
    ];
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="flex {{ $layout }} gap-2 {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     x-data
>
    @foreach($networks as $network)
        @if($network === 'copy')
            <button @click="navigator.clipboard.writeText(window.location.href); $el.textContent = '{{ __('layup::frontend.share_buttons.copied') }}'"
                    class="inline-flex items-center gap-1 px-3 py-2 rounded text-sm text-white transition-colors"
                    style="background-color: #6b7280">
                @if($style !== 'text') 
                @endif
                @if($style !== 'icon'){{ __('layup::frontend.share_buttons.copy_link') }}
                @endif
            </button>
        @elseif(isset($shareUrls[$network]))
            @php $s = $shareUrls[$network]; @endphp
            <a href="{{ $s['url'] }}" target="{{ $target }}" rel="noopener"
               class="inline-flex items-center gap-1 px-3 py-2 rounded text-sm text-white hover:opacity-90 transition-opacity"
               style="background-color: {{ $s['bg'] }}">
                @if($style !== 'text')<span class="font-bold">{{ $s['icon'] }}</span>
                @endif
                @if($style !== 'icon'){{ $s['label'] }}
                @endif
            </a>
        @endif
    @endforeach
</div>
