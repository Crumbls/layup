<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.$entangle('{{ $getStatePath() }}'),
            hovering: null,
        }"
        class="lyp-span-picker"
    >
        <div class="lyp-span-header">
            <span class="lyp-span-label">{{ $getBreakpointLabel() }}</span>
            <span class="lyp-span-value" x-text="state + '/12'"></span></div>
        <div class="lyp-span-blocks">
            <template x-for="i in 12" :key="i">
                <button
                    type="button"
                    @click="state = i"
                    @mouseenter="hovering = i"
                    @mouseleave="hovering = null"
                    :class="{
                        'lyp-span-block': true,
                        'lyp-span-block--active': i <= state,
                        'lyp-span-block--hover': hovering !== null && i <= hovering && i > state,
                        'lyp-span-block--fade': hovering !== null && hovering < state && i > hovering && i <= state,
                    }"
                    :style="i <= state ? 'background: {{ $getColor() }}; border-color: {{ $getColor() }}' : ''"
                >
                    <span
                        class="lyp-span-block-num"
                        x-show="i === state || i === hovering"
                        x-text="i"
                    ></span></button></template></div></div></x-dynamic-component>
