<x-filament::modal id="delete-row-modal"

                   icon="heroicon-o-exclamation-triangle"
                   icon-color="danger"
>
    <x-slot name="heading">
        Delete Row
    </x-slot>

    <div class="py-4">
        Are you sure you want to delete this row?
    </div>

    <x-slot name="footerActions">
        <x-filament::button
                color="gray"
                x-on:click="$dispatch('close-modal', { id: 'delete-row-modal' })"
        >
            Cancel
        </x-filament::button>

        <x-filament::button
                color="danger"
                @click="rows.deleteConfirm"
        >
            Delete Row
        </x-filament::button></x-slot></x-filament::modal>
