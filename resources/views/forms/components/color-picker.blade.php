@php
    $swatches = $getSwatches();
    $allowCustom = $getAllowCustom();
    $statePath = $getStatePath();
    $contrastColors = [];
    foreach ($swatches as $name => $hex) {
        $contrastColors[$name] = $field->getContrastColor($hex);
    }
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.$entangle('{{ $statePath }}'),
            pick(hex) {
                this.state = this.state === hex ? null : hex
            },
            isPreset() {
                if (!this.state) return false
                return Object.values(@js($swatches)).some(
                    h => h.toLowerCase() === this.state.toLowerCase()
                )
            }
        }"
        class="flex flex-wrap items-center gap-1.5"
    >
        @foreach($swatches as $name => $hex)
            <button
                type="button"
                x-on:click="pick('{{ $hex }}')"
                class="relative w-7 h-7 rounded-full border-2 transition-all duration-100 focus:outline-none"
                x-bind:class="state && state.toLowerCase() === '{{ strtolower($hex) }}' ? 'border-gray-900 dark:border-white scale-110' : 'border-transparent hover:scale-110'"
                style="background-color: {{ $hex }}"
                title="{{ ucfirst($name) }}"
            >
                <span
                    x-show="state && state.toLowerCase() === '{{ strtolower($hex) }}'"
                    x-cloak
                    class="absolute inset-0 flex items-center justify-center"
                >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor" style="color: {{ $contrastColors[$name] }}">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            </button>
        @endforeach

        @if($allowCustom)
            <label
                class="relative w-7 h-7 rounded-full border-2 transition-all duration-100 cursor-pointer overflow-hidden"
                x-bind:class="state && !isPreset() ? 'border-gray-900 dark:border-white scale-110' : 'border-transparent hover:scale-110'"
                title="Custom"
            >
                <span
                    x-show="!state || isPreset()"
                    class="absolute inset-0 rounded-full"
                    style="background: conic-gradient(#ef4444, #f59e0b, #22c55e, #3b82f6, #8b5cf6, #ef4444)"
                ></span>
                <span
                    x-show="state && !isPreset()"
                    x-cloak
                    class="absolute inset-0 rounded-full"
                    x-bind:style="'background-color: ' + state"
                ></span>
                <input
                    type="color"
                    x-model="state"
                    class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                />
            </label>
        @endif
    </div>
</x-dynamic-component>
