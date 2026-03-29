@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $pos = $data['image_position'] ?? 'left';
    $w = match($data['image_width'] ?? '1/2') { '1/3' => 'w-1/3', '2/5' => 'w-2/5', '3/5' => 'w-3/5', default => 'w-1/2' };
    $tw = match($data['image_width'] ?? '1/2') { '1/3' => 'w-2/3', '2/5' => 'w-3/5', '3/5' => 'w-2/5', default => 'w-1/2' };
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="flex flex-col md:flex-row gap-4 md:gap-8 items-center {{ $pos === 'right' ? 'md:flex-row-reverse' : '' }} {{ $vis }} {{ $data['class'] ?? '' }}" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    @if(!empty($data['image']))<div class="w-full md:{{ $w }}"><img src="{{ asset('storage/' . $data['image']) }}" alt="" class="w-full h-auto rounded-lg" /></div>@endif
    <div class="w-full md:{{ $tw }}">
        @if(!empty($data['heading']))<h2 class="text-2xl font-bold mb-4">{{ $data['heading'] }}</h2>@endif
        @if(!empty($data['content']))<div class="prose">{!! $data['content'] !!}</div>@endif
    </div>
</div>
