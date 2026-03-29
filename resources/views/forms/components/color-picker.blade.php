@php
    $swatches = $getSwatches();
    $allowCustom = $getAllowCustom();
    $fieldId = $getId();
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
            showCustom: false,
            isCustom: false,
            swatches: @js($swatches),
            init() {
                this.checkIfCustom()
                this.$watch('state', () => this.checkIfCustom())
            },
            checkIfCustom() {
                if (!this.state) {
                    this.isCustom = false
                    this.showCustom = false
                    return
                }
                let isPreset = Object.values(this.swatches).some(
                    hex => hex.toLowerCase() === this.state.toLowerCase()
                )
                this.isCustom = !isPreset
                this.showCustom = this.isCustom
            },
            select(hex) {
                this.state = hex
                this.showCustom = false
                this.isCustom = false
            },
            clear() {
                this.state = null
                this.showCustom = false
                this.isCustom = false
            },
            openCustom() {
                this.showCustom = true
                this.isCustom = true
                if (!this.state) {
                    this.state = '#000000'
                }
            }
        }"
        class="flex flex-wrap items-center gap-2"
    >
        {{-- Swatch buttons --}}
        @foreach($swatches as $name => $hex)
            <button
                type="button"
                x-on:click="select('{{ $hex }}')"
                class="relative w-8 h-8 rounded-lg border-2 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-primary-500"
                x-bind:class="state && state.toLowerCase() === '{{ strtolower($hex) }}' ? 'border-gray-900 dark:border-white scale-110 ring-2 ring-primary-500' : 'border-gray-200 dark:border-gray-600 hover:scale-105 hover:border-gray-400 dark:hover:border-gray-400'"
                style="background-color: {{ $hex }}"
                title="{{ ucfirst($name) }}: {{ $hex }}"
            >
                <span
                    x-show="state && state.toLowerCase() === '{{ strtolower($hex) }}'"
                    x-cloak
                    class="absolute inset-0 flex items-center justify-center"
                >
                    <svg class="w-4 h-4 drop-shadow-sm" viewBox="0 0 20 20" fill="currentColor"
                         style="color: {{ $contrastColors[$name] }}">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            </button>
        @endforeach

        {{-- Custom color toggle --}}
        @if($allowCustom)
            <button
                type="button"
                x-on:click="showCustom ? clear() : openCustom()"
                class="relative w-8 h-8 rounded-lg border-2 transition-all duration-150 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-primary-500"
                x-bind:class="isCustom ? 'border-gray-900 dark:border-white scale-110 ring-2 ring-primary-500' : 'border-gray-200 dark:border-gray-600 hover:scale-105 hover:border-gray-400 dark:hover:border-gray-400'"
                title="Custom color"
            >
                <span
                    x-show="!isCustom"
                    class="absolute inset-0"
                    style="background: conic-gradient(#ef4444, #f59e0b, #22c55e, #3b82f6, #8b5cf6, #ef4444)"
                ></span>
                <span
                    x-show="isCustom"
                    x-cloak
                    class="absolute inset-0"
                    x-bind:style="'background-color: ' + (state || '#000000')"
                ></span>
            </button>
        @endif

        {{-- Clear button --}}
        <button
            type="button"
            x-show="state"
            x-cloak
            x-on:click="clear()"
            class="w-8 h-8 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center text-gray-400 dark:text-gray-500 hover:border-gray-400 dark:hover:border-gray-400 hover:text-gray-500 dark:hover:text-gray-400 transition-all duration-150 focus:outline-none"
            title="Clear"
        >
            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>

        {{-- Custom hex input --}}
        @if($allowCustom)
            <div
                x-show="showCustom"
                x-cloak
                x-transition
                class="flex items-center gap-2 ml-1"
            >
                <input
                    type="color"
                    x-model="state"
                    class="w-8 h-8 rounded cursor-pointer border border-gray-300 dark:border-gray-600 p-0"
                />
                <input
                    type="text"
                    x-model="state"
                    placeholder="#000000"
                    maxlength="7"
                    class="w-20 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-mono px-2 py-1.5 focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                />
            </div>
        @endif
    </div>
</x-dynamic-component>
