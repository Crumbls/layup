@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $words = $data['words'] ?? [];
    // Handle both simple array and Filament repeater format
    $wordList = array_map(fn($w) => is_array($w) ? ($w['word'] ?? '') : $w, $words);
    $speed = $data['speed'] ?? 100;
    $pause = $data['pause'] ?? 2000;
    $loop = !empty($data['loop']);
    $cursorColor = $data['cursor_color'] ?? 'var(--layup-primary)';
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="text-2xl md:text-3xl font-bold {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
     x-data="{
        words: {{ json_encode(array_values($wordList)) }},
        current: '',
        wordIdx: 0,
        charIdx: 0,
        deleting: false,
        init() {
            this.type();
        },
        type() {
            const word = this.words[this.wordIdx] || '';
            if (!this.deleting) {
                this.current = word.substring(0, this.charIdx + 1);
                this.charIdx++;
                if (this.charIdx >= word.length) {
                    setTimeout(() => { this.deleting = true; this.type(); }, {{ $pause }});
                    return;
                }
            } else {
                this.current = word.substring(0, this.charIdx - 1);
                this.charIdx--;
                if (this.charIdx <= 0) {
                    this.deleting = false;
                    this.wordIdx = {{ $loop ? '(this.wordIdx + 1) % this.words.length' : 'Math.min(this.wordIdx + 1, this.words.length - 1)' }};
                    @unless($loop)if (this.wordIdx >= this.words.length - 1 && this.charIdx <= 0) { this.current = this.words[this.wordIdx]; return; }@endunless
                }
            }
            setTimeout(() => this.type(), this.deleting ? {{ intval($speed / 2) }} : {{ $speed }});
        }
     }"
>
    {{ $data['prefix'] ?? '' }}<span x-text="current"></span><span class="animate-pulse" style="color: {{ $cursorColor }}">|</span>{{ $data['suffix'] ?? '' }}
</div>
