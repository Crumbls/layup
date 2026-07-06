@php
    $settings = $section['settings'] ?? [];
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($settings['hide_on'] ?? []);
    $sectionStyles = \Crumbls\Layup\View\Section::buildSectionStyles($settings);
    $overlayColor = $settings['overlay_color'] ?? '#000000';
    $overlayOpacity = ($settings['overlay_opacity'] ?? 0) / 100;
    $hasOverlay = $overlayOpacity > 0;
@endphp
<section @if(!empty($settings['id']))id="{{ $settings['id'] }}"@endif
         class="relative {{ $vis }} {{ $settings['class'] ?? '' }}"
         style="{{ $sectionStyles }}"
         {!! \Crumbls\Layup\View\BaseView::animationAttributes($settings) !!}
>
    {{-- Background video --}}
    @if(!empty($settings['background_video']))
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover -z-10">
            <source src="{{ $settings['background_video'] }}" type="video/mp4" /></video>
    @endif

    {{-- Overlay --}}
    @if($hasOverlay)
        <div class="absolute inset-0" style="background-color: {{ $overlayColor }}; opacity: {{ $overlayOpacity }}; z-index: 1"></div>
    @endif

    {{-- Content: render rows --}}
    <div class="relative space-y-4" style="z-index: 2">
        @foreach($section['rows'] ?? [] as $row)
            {!! $row->render() !!}
        @endforeach
    </div></section>
