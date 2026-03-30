@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $percent = (int)($data['percent'] ?? 75);
    $size = (int)($data['size'] ?? 120);
    $stroke = (int)($data['stroke_width'] ?? 8);
    $radius = ($size - $stroke) / 2;
    $circumference = 2 * M_PI * $radius;
    $color = $data['color'] ?? 'var(--layup-primary)';
    $animate = ($data['animate'] ?? true) ? 'true' : 'false';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="text-center {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     x-data="{ shown: false, percent: 0 }"
     x-intersect.once="shown = true; if ({{ $animate }}) { let t = 0; const iv = setInterval(() => { t += 2; percent = Math.min(t, {{ $percent }}); if (t >= {{ $percent }}) clearInterval(iv); }, 20); } else { percent = {{ $percent }}; }"
>
    <svg width="{{ $size }}" height="{{ $size }}" class="inline-block">
        <circle cx="{{ $size/2 }}" cy="{{ $size/2 }}" r="{{ $radius }}"
                fill="none" stroke="var(--layup-border)" stroke-width="{{ $stroke }}" />
        <circle cx="{{ $size/2 }}" cy="{{ $size/2 }}" r="{{ $radius }}"
                fill="none" stroke="{{ $color }}" stroke-width="{{ $stroke }}"
                stroke-linecap="round"
                stroke-dasharray="{{ $circumference }}"
                :stroke-dashoffset="{{ $circumference }} - ({{ $circumference }} * percent / 100)"
                transform="rotate(-90 {{ $size/2 }} {{ $size/2 }})"
                style="transition: stroke-dashoffset 0.1s ease" />
        <text x="50%" y="50%" text-anchor="middle" dominant-baseline="central"
              class="text-xl font-bold fill-current" x-text="percent + '%'">{{ $percent }}%</text>
    </svg>
    @if(!empty($data['title']))
        <div class="mt-2 text-sm font-medium">{{ $data['title'] }}</div>
    @endif
</div>
