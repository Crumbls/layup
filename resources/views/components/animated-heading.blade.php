@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $tag = $data['tag'] ?? 'h2';
    $effect = $data['effect'] ?? 'highlight';
    $color = $data['accent_color'] ?? 'var(--layup-primary)';
    $decoration = match($effect) {
        'underline' => "border-bottom: 3px solid {$color}; padding-bottom: 2px",
        'circle' => "border: 2px solid {$color}; border-radius: 9999px; padding: 0.1em 0.3em",
        'strikethrough' => "text-decoration: line-through; text-decoration-color: {$color}; text-decoration-thickness: 3px",
        default => "background: linear-gradient(transparent 60%, {$color}40 60%)",
    };
@endphp
<{{ $tag }} @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="font-bold {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>{{ $data['before_text'] ?? '' }} <span style="{{ $decoration }}">{{ $data['animated_text'] ?? '' }}</span> {{ $data['after_text'] ?? '' }}</{{ $tag }}>
