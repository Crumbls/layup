<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="rounded overflow-hidden {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}" style="height:{{ $data['height'] ?? '300px' }};@if(!empty($data['inline_css'])){{ $data['inline_css'] }}@endif" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    @if(!empty($data['embed']))
        @if(config('layup.allow_raw_html', true))
            {!! $data['embed'] !!}
        @else
            {{ $data['embed'] }}
        @endif
    @elseif(!empty($data['address']))
        <iframe
            src="https://maps.google.com/maps?q={{ urlencode($data['address']) }}&z={{ $data['zoom'] ?? '13' }}&output=embed"
            class="w-full h-full border-0"
            allowfullscreen
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
        ></iframe>
    @endif
</div>
