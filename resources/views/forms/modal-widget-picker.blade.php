<template x-if="picker.open">
    <div class="lyp-picker-overlay" @click.self="closePicker()" @keydown.escape.window="closePicker()">
        <div class="lyp-picker-modal" @click.stop>
            <div class="lyp-picker-header">
                <input
                        type="text"
                        class="lyp-picker-search"
                        placeholder="{{ __('layup::builder.search_widgets') }}"
                        x-model="picker.search"
                        x-ref="pickerSearch"
                        @keydown.escape="closePicker()"
                /></div>
            <div class="lyp-picker-body">
                {{-- Recently Used --}}
                <template x-if="!picker.search && getRecentWidgets().length > 0">
                    <div>
                        <div class="lyp-picker-cat-label">{{ __('layup::builder.recently_used') }}</div>
                        <div class="lyp-picker-grid">
                            <template x-for="w in getRecentWidgets()" :key="w.type">
                                <button
                                        @click="selectWidget(w.type)"
                                        class="lyp-picker-item"
                                        draggable="true"
                                        @dragstart="onPickerDragStart($event, w.type)"
                                        @dragend="onPickerDragEnd()"
                                >
                                    <span x-html="getIconSvg(w.icon)" class="lyp-picker-item-icon"></span>
                                    <span class="lyp-picker-item-label" x-text="w.label"></span></button></template></div></div></template>

                {{-- Widget Categories --}}
                <template x-for="cat in getFilteredWidgetCategories()" :key="cat.name">
                    <div>
                        <div class="lyp-picker-cat-label" x-text="cat.name"></div>
                        <div class="lyp-picker-grid">
                            <template x-for="w in cat.widgets" :key="w.type">
                                <button
                                        @click="selectWidget(w.type)"
                                        class="lyp-picker-item"
                                        draggable="true"
                                        @dragstart="onPickerDragStart($event, w.type)"
                                        @dragend="onPickerDragEnd()"
                                >
                                    <span x-html="getIconSvg(w.icon)" class="lyp-picker-item-icon"></span>
                                    <span class="lyp-picker-item-label" x-text="w.label"></span></button></template></div></div></template>
                <template x-if="getFilteredWidgetCategories().length === 0">
                    <div class="lyp-picker-empty">{{ __('layup::builder.no_widgets_match') }}</div></template></div></div></div></template>