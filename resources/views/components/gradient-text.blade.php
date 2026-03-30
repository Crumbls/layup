@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $tag = $data['tag'] ?? 'h2';
    $from = $data['from_color'] ?? 'var(--layup-primary)';
    $to = $data['to_color'] ?? 'var(--layup-accent)';
    $via = $data['via_color'] ?? '';
    $dir = $data['direction'] ?? 'to right';
    $gradient = $via ? "linear-gradient({$dir}, {$from}, {$via}, {$to})" : "linear-gradient({$dir}, {$from}, {$to})";
@endphp
<{{ $tag }} @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="font-bold {{ $vis }} {{ $data['class'] ?? '' }}"
     style="background: {{ $gradient }}; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; {{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>{{ $data['text'] ?? '' }}</{{ $tag }}>
