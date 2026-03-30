@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $iconStyle = $data['icon_style'] ?? 'check';
    $iconColor = $data['icon_color'] ?? 'var(--layup-success)';
    $layout = $data['layout'] ?? 'list';
    $gridClass = match($layout) {
        'grid-2' => 'grid grid-cols-1 md:grid-cols-2 gap-4',
        'grid-3' => 'grid grid-cols-1 md:grid-cols-3 gap-4',
        default => 'space-y-3',
    };
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $gridClass }} {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @foreach(($data['features'] ?? []) as $i => $feature)
        <div class="flex gap-3">
            <span class="shrink-0 font-bold" style="color: {{ $iconColor }}">
                @switch($iconStyle)
                    @case('arrow') → @break
                    @case('dot') ● @break
                    @case('number') {{ $i + 1 }}. @break
                    @default ✓
                @endswitch
            </span>
            <div>
                @if(!empty($feature['title']))
                    <div class="font-semibold">{{ $feature['title'] }}</div>
                @endif
                @if(!empty($feature['description']))
                    <div class="text-sm text-gray-600 dark:text-gray-300">{{ $feature['description'] }}</div>
                @endif
            </div>
        </div>
    @endforeach
</div>
