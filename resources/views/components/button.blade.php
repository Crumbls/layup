@php
    $hasHover = !empty($data['hover_bg_color']) || !empty($data['hover_text_color']);
    $baseStyle = \Crumbls\Layup\View\BaseView::buildInlineStyles($data);
    if (!empty($data['bg_color'])) $baseStyle .= " background-color: {$data['bg_color']};";
    if (!empty($data['text_color_override'])) $baseStyle .= " color: {$data['text_color_override']};";
    $hoverStyle = $baseStyle;
    if (!empty($data['hover_bg_color'])) $hoverStyle = str_replace("background-color: {$data['bg_color']}", "background-color: {$data['hover_bg_color']}", $hoverStyle) ?: $hoverStyle . " background-color: {$data['hover_bg_color']};";
    if (!empty($data['hover_text_color'])) $hoverStyle .= " color: {$data['hover_text_color']};";
@endphp
<a href="{{ $data['url'] ?? '#' }}"
   @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
   class="inline-block rounded font-medium transition-all duration-200
       {{ match($data['style'] ?? 'primary') {
           'primary'   => 'layup-bg-primary text-white layup-hover-bg-primary',
           'secondary' => 'layup-bg-secondary text-white layup-hover-bg-secondary',
           'outline'   => 'border layup-border-primary layup-text-primary hover:bg-gray-100 dark:hover:bg-gray-800',
           'ghost'     => 'layup-text-primary hover:bg-gray-100 dark:hover:bg-gray-800',
           default     => 'layup-bg-primary text-white layup-hover-bg-primary',
       } }}
       {{ match($data['size'] ?? 'md') {
           'sm' => 'px-3 py-1.5 text-sm',
           'md' => 'px-5 py-2.5 text-base',
           'lg' => 'px-7 py-3 text-lg',
           default => 'px-5 py-2.5 text-base',
       } }}
       {{ \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []) }} {{ $data['class'] ?? '' }}"
   @if(!empty($data['new_tab'])) target="_blank" rel="noopener noreferrer" @endif
   @if($hasHover)
       x-data="{ hover: false }"
       @mouseenter="hover = true"
       @mouseleave="hover = false"
       :style="hover ? '{{ $hoverStyle }}' : '{{ $baseStyle }}'"
   @else
       style="{{ $baseStyle }}"
   @endif
   {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    {{ $data['label'] ?? __('layup::frontend.button.click_me') }}
</a>
