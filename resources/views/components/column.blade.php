@php
    $isGrid = ($layout ?? 'flex') === 'grid';
    $gridClasses = [];
    foreach (['sm' => '', 'md' => 'md:', 'lg' => 'lg:', 'xl' => 'xl:'] as $breakpoint => $prefix) {
        $value = $span[$breakpoint] ?? 12;
        $gridClasses[] = $prefix . 'col-span-' . (in_array($value, range(1, 12)) ? (int) $value : 12);
    }
    $widthMap = [
        1 => '1/12',
        2 => '2/12',
        3 => '3/12',
        4 => '4/12',
        5 => '5/12',
        6 => '6/12',
        7 => '7/12',
        8 => '8/12',
        9 => '9/12',
        10 => '10/12',
        11 => '11/12',
        12 => 'full',
    ];

    $sm = $widthMap[$span['sm'] ?? 12] ?? 'full';
    $md = $widthMap[$span['md'] ?? 12] ?? 'full';
    $lg = $widthMap[$span['lg'] ?? 12] ?? 'full';
    $xl = $widthMap[$span['xl'] ?? 12] ?? 'full';

    // Gutter classes: first=pl, last=pr, middle=px
    $isOnly = $isFirst && $isLast;
    if ($isOnly) {
        $gutter = '';
    } elseif ($isFirst) {
        $gutter = 'md:pr-2';
    } elseif ($isLast) {
        $gutter = 'md:pl-2';
    } else {
        $gutter = 'md:px-2';
    }
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $columnClasses = $isGrid ? 'min-w-0 ' . implode(' ', $gridClasses) : "w-{$sm} md:w-{$md} lg:w-{$lg} xl:w-{$xl} {$gutter}";
@endphp
<div
    @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
    class="{{ $columnClasses }} space-y-4 {{ $vis }} {{ $data['class'] ?? '' }}"
    style="
        @if(!empty($data['align_self']) && $data['align_self'] !== 'auto')align-self: {{ match($data['align_self']) { 'start' => 'flex-start', 'end' => 'flex-end', default => $data['align_self'] } }};@endif
        @if(!empty($data['overflow']) && $data['overflow'] !== 'visible')overflow: {{ $data['overflow'] }};@endif
        {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}
    "
    {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @foreach($children as $child)
        {!! $child->render() !!}
    @endforeach
</div>
