@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $style = $data['style'] ?? 'bullet';
    $color = $data['icon_color'] ?? 'var(--layup-primary)';
    $marker = match($style) {
        'check' => '✓',
        'arrow' => '→',
        'number' => null,
        'none' => null,
        default => '•',
    };
    $items = array_map(fn($i) => is_array($i) ? ($i['text'] ?? '') : $i, $data['items'] ?? []);
@endphp
<{{ $style === 'number' ? 'ol' : 'ul' }}
    @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
    class="space-y-2 {{ $style === 'none' || $marker ? 'list-none' : 'list-decimal pl-5' }} {{ $vis }} {{ $data['class'] ?? '' }}"
    style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
    {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @foreach($items as $i => $item)
        @if(!empty($item))
            <li class="flex gap-2">
                @if($style === 'number')
                    <span class="font-bold shrink-0" style="color: {{ $color }}">{{ $i + 1 }}.</span>
                @elseif($marker)
                    <span class="shrink-0" style="color: {{ $color }}">{{ $marker }}</span>
                @endif
                <span>{{ $item }}</span>
            </li>
        @endif
    @endforeach
</{{ $style === 'number' ? 'ol' : 'ul' }}>
