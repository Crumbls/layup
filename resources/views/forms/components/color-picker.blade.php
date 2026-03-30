@php
    $swatches = $getSwatches();
    $allowCustom = $getAllowCustom();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.$entangle('{{ $statePath }}'),
            customColor: null,
            presets: @js($swatches),
            init() {
                if (this.state && !this.matchesPreset(this.state)) {
                    this.customColor = this.state
                }
            },
            matchesPreset(val) {
                if (!val) return false
                let v = val.toLowerCase()
                return Object.values(this.presets).some(h => h.toLowerCase() === v)
            },
            pickPreset(hex) {
                this.state = this.state === hex ? null : hex
            },
            onCustomInput(e) {
                this.customColor = e.target.value
                this.state = e.target.value
            }
        }"
        style="display: flex; flex-wrap: wrap; gap: 6px;"
    >
        @foreach($swatches as $name => $hex)
            <button
                type="button"
                x-on:click="pickPreset('{{ $hex }}')"
                x-bind:style="state && state.toLowerCase() === '{{ strtolower($hex) }}'
                    ? 'display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 4px; border-radius: 9999px; border: none; cursor: pointer; font-size: 12px; font-weight: 500; transition: all 150ms; background: color-mix(in oklab, {{ $hex }} 12%, transparent); color: var(--gray-950); box-shadow: 0 0 0 2px {{ $hex }};'
                    : 'display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 4px; border-radius: 9999px; border: none; cursor: pointer; font-size: 12px; font-weight: 500; transition: all 150ms; background: var(--gray-100); color: var(--gray-700);'"
            >
                <span style="display: block; width: 20px; height: 20px; border-radius: 50%; background-color: {{ $hex }}; flex-shrink: 0;"></span>
                {{ ucfirst($name) }}
            </button>
        @endforeach

        @if($allowCustom)
            <label
                x-bind:style="customColor && state === customColor
                    ? 'display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 4px; border-radius: 9999px; border: none; cursor: pointer; font-size: 12px; font-weight: 500; transition: all 150ms; background: color-mix(in oklab, ' + customColor + ' 12%, transparent); color: var(--gray-950); box-shadow: 0 0 0 2px ' + customColor + ';'
                    : 'display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 4px; border-radius: 9999px; border: none; cursor: pointer; font-size: 12px; font-weight: 500; transition: all 150ms; background: var(--gray-100); color: var(--gray-700);'"
            >
                <span style="position: relative; display: block; width: 20px; height: 20px; border-radius: 50%; flex-shrink: 0; overflow: hidden;">
                    <span
                        x-show="!(customColor && state === customColor)"
                        style="position: absolute; inset: 0; border-radius: 50%; background: conic-gradient(#ef4444, #f59e0b, #22c55e, #3b82f6, #8b5cf6, #ef4444);"
                    ></span>
                    <span
                        x-show="customColor && state === customColor"
                        x-cloak
                        style="position: absolute; inset: 0; border-radius: 50%;"
                        x-bind:style="'position: absolute; inset: 0; border-radius: 50%; background-color: ' + customColor"
                    ></span>
                </span>
                <span x-show="!(customColor && state === customColor)">Custom</span>
                <span x-show="customColor && state === customColor" x-cloak x-text="customColor" style="font-family: ui-monospace, monospace;"></span>
                <input
                    type="color"
                    x-bind:value="customColor || '#000000'"
                    x-on:input="onCustomInput($event)"
                    style="position: absolute; width: 0; height: 0; opacity: 0; pointer-events: none;"
                />
            </label>
        @endif
    </div>
</x-dynamic-component>
