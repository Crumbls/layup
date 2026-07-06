@php
    $fullWidth = !empty($data['full_width']);
    $maxWidth = \Crumbls\Layup\Support\PageLayout::resolve($page ?? null);
@endphp
<div
    @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
    style="
        @if(!empty($data['background_color']))background-color: {{ $data['background_color'] }};@endif
        @if(!empty($data['inline_css'])){{ $data['inline_css'] }}@endif
    "
    @if(!empty($data['class']))class="{{ $data['class'] }}"@endif
>
    <div class="flex flex-wrap {{ $fullWidth ? '' : $maxWidth . ' mx-auto' }}"
        style="
            @if(!empty($data['justify']) && $data['justify'] !== 'start')justify-content: {{ match($data['justify']) { 'center' => 'center', 'end' => 'flex-end', 'between' => 'space-between', 'around' => 'space-around', 'evenly' => 'space-evenly', default => 'flex-start' } }};@endif
            @if(!empty($data['align']) && $data['align'] !== 'stretch')align-items: {{ match($data['align']) { 'start' => 'flex-start', 'end' => 'flex-end', 'center' => 'center', 'baseline' => 'baseline', default => 'stretch' } }};@endif
        "
    >
        @foreach($children as $child)
            @php
                $child->setPosition(
                    first: $loop->first,
                    last: $loop->last,
                );
            @endphp
            {!! $child->render() !!}
        @endforeach
    </div></div>
