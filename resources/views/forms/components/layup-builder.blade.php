<x-dynamic-component
        :component="$getFieldWrapperView()"
        :field="$field"
>
    <div
            wire:ignore
            x-data="layupBuilder({
                state: $wire.$entangle(@js($getStatePath())),
                statePath: @js($getStatePath()),
                componentKey: @js($getKey()),
                content: @js($getState()),
                breakpoints: @js($breakpoints),
                defaultBreakpoint: @js($defaultBreakpoint),
                rowTemplates: @js($rowTemplates),
                widgetRegistry: @js($widgetRegistry),
                translations: @js($translations),
                geocode: function() {
                console.log('tf');
                }
            })"

            {{ $getExtraAttributeBag() }}
            class="lyp-wrap"
            x-on:content-updated.window="pushHistory(); content = Array.isArray($event.detail) ? $event.detail[0] : $event.detail"
            @keydown.window="onKeyDown($event)"
    >


        {{-- Top Bar --}}
        <div class="lyp-toolbar">
            <div class="lyp-bp-group">
                <template x-for="(bp, key) in breakpoints" :key="key">
                    <button
                            type="button"
                            @click="currentBreakpoint = key"
                            :class="{'lyp-bp-btn': true, 'active': currentBreakpoint === key}"
                            :title="bp.label + ' (' + bp.width + 'px)'"
                    >
                        <template x-if="bp.icon === 'heroicon-o-device-phone-mobile'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lyp-bp-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                        </template>
                        <template x-if="bp.icon === 'heroicon-o-device-tablet'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lyp-bp-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5h3m-6.75 2.25h10.5a2.25 2.25 0 002.25-2.25v-15a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 4.5v15a2.25 2.25 0 002.25 2.25z"/></svg>
                        </template>
                        <template x-if="bp.icon === 'heroicon-o-computer-desktop'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lyp-bp-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z"/></svg>
                        </template>
                        <template x-if="bp.icon === 'heroicon-o-tv'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lyp-bp-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H2.625c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125z"/></svg>
                        </template>
                        <span class="lyp-bp-label" x-text="bp.label"></span>
                    </button>
                </template>
            </div>

            <div class="lyp-toolbar-right">
                <div class="lyp-undo-group">
                    <button type="button" @click="undo()" :disabled="historyIndex <= 0" class="lyp-toolbar-icon" title="{{ __('layup::builder.undo') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                    </button>
                    <button type="button" @click="redo()" :disabled="historyIndex >= history.length - 1" class="lyp-toolbar-icon" title="{{ __('layup::builder.redo') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l6-6m0 0l-6-6m6 6H9a6 6 0 000 12h3"/></svg>
                    </button>
                </div>

                {{-- Ruler Toggle --}}
                <button type="button" @click="showRuler = !showRuler" class="lyp-toolbar-icon" :title="showRuler ? '{{ __('layup::builder.hide_ruler') }}' : '{{ __('layup::builder.show_ruler') }}'" :style="showRuler ? 'color: var(--primary-500)' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                </button>
            </div>
        </div>

        {{-- Canvas --}}
        <div class="lyp-canvas">
            <div class="lyp-canvas-inner" :style="'max-width:' + breakpoints[currentBreakpoint].width + 'px'">
                {{-- Ruler --}}
                <div class="lyp-ruler" :class="{ 'lyp-ruler--hidden': !showRuler }">
                    <template x-for="i in 12" :key="i">
                        <div class="lyp-ruler-cell" x-text="i"></div>
                    </template>
                </div>

                {{-- Rows --}}
                <div class="lyp-rows group">
                    {{-- Insert zone before first row --}}
                    <div class="lyp-insert-zone" x-data="{ showTemplates: false }" @mouseenter="$el.classList.add('lyp-insert-zone--hover')" @mouseleave="if(!showTemplates) $el.classList.remove('lyp-insert-zone--hover')">
                        <div class="lyp-insert-line">
                            <button
                                    type="button"
                                    @click.stop="showTemplates = !showTemplates" class="lyp-insert-btn" title="{{ __('layup::builder.add_row') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </button>
                        </div>
                        <div x-show="showTemplates" @click.away="showTemplates = false; $el.closest('.lyp-insert-zone').classList.remove('lyp-insert-zone--hover')" x-transition class="lyp-templates lyp-templates--inline">
                            <p>{{ __('layup::builder.choose_layout') }}</p>
                            <div class="lyp-templates-grid">
                                <template x-for="(template, idx) in rowTemplates" :key="idx">
                                    <button type="button" @click="rowAddAt(template, 0); showTemplates = false; $el.closest('.lyp-insert-zone').classList.remove('lyp-insert-zone--hover')" class="lyp-tpl-btn">
                                        <template x-for="(span, ci) in template" :key="ci">
                                            <div class="lyp-tpl-col" :style="'flex:' + span + ' ' + span + ' 0%'"></div>
                                        </template>
                                    </button>
                                </template>

                            </div>
                        </div>
                    </div>

                    <template x-for="(row, rowIndex) in content.rows" :key="row.id">
                        <div>
                            {{-- Row drop indicator --}}
                            <div
                                    class="lyp-row-drop-indicator"
                                    :class="{ 'lyp-row-drop-indicator--active': rowDrag.dropIndex === rowIndex }"
                                    @dragover.prevent="if(rowDrag.active) rowDrag.dropIndex = rowIndex"
                                    @drop.prevent="onRowDrop($event)"
                            ></div>
                            <div
                                    class="lyp-row"
                                    :class="{ 'lyp-row--dragging': rowDrag.active && rowDrag.rowId === row.id }"
                                    @click.self="rowEdit(row.id)"
                                    @dragover.prevent.stop="onRowDragOver($event, rowIndex)"
                                    @drop.prevent="onRowDrop($event)"
                            >
                                <div class="lyp-row-header">
                                    <div style="display:flex;align-items:center;gap:0.375rem">
                                        <span
                                                class="lyp-drag-handle"
                                                draggable="true"
                                                @dragstart.stop="onRowDragStart($event, row.id, rowIndex)"
                                                @dragend="onRowDragEnd()"
                                                title="{{ __('layup::builder.drag_to_reorder') }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5"/></svg>
                                        </span>
                                        <span class="lyp-row-label" x-text="translations.row_label.replace(':number', rowIndex + 1)"></span>
                                    </div>
                                    <div class="lyp-actions group-hover:opacity-100">
                                        <button
                                                type="button"
                                                @click.stop="columnAdd(row.id)" class="lyp-action-btn" title="{{ __('layup::builder.add_column') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                        </button>
                                        <button
                                                type="button"
                                                @click.stop="rowDuplicate(row.id)"
                                                class="lyp-action-btn"
                                                title="{{ __('layup::builder.duplicate_row') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
                                        </button>
                                        <button type="button" @click.stop="rowEdit(row.id)" class="lyp-action-btn" title="{{ __('layup::builder.row_settings') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </button>
                                        <button type="button"
                                                @click.stop="rowDelete(row.id)"
                                                class="lyp-action-btn lyp-action-btn--danger" title="{{ __('layup::builder.delete_row') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Columns --}}
                                <div class="lyp-columns" :style="'--lyp-col-gap:' + (row.settings.gap ? row.settings.gap.replace('gap-','').replace('0','0px').replace('2','0.5rem').replace('4','1rem').replace('6','1.5rem').replace('8','2rem').replace('12','3rem') : '1rem')">
                                    <template x-for="(col, colIndex) in row.columns" :key="col.id">
                                        <div style="display: contents;">
                                            {{-- Resize handle before column (except first) --}}
                                            <template x-if="colIndex > 0">
                                                <div
                                                        class="lyp-resize-handle"
                                                        @mousedown.prevent="startColumnResize(row.id, colIndex, $event)"
                                                        title="{{ __('layup::builder.drag_to_resize') }}"
                                                >
                                                    <div class="lyp-resize-handle-bar"></div>
                                                </div>
                                            </template>
                                            <div
                                                    class="lyp-col"
                                                    :class="{ 'lyp-col--drop-target': drag.active && (drag.fromPicker || !(drag.sourceRowId === row.id && drag.sourceColId === col.id && col.widgets.length === 1)) }"
                                                    :style="'grid-column: span ' + getColSpan(col) + ' / span ' + getColSpan(col)"
                                                    @click.self="columnEdit(row.id, col.id)"
                                                    @dragover.prevent="onDragOverCol($event, row.id, col.id)"
                                                    @dragleave="onDragLeaveCol($event)"
                                                    @drop.prevent="onDropCol($event, row.id, col.id)"
                                            >
                                                <div class="lyp-col-header">
                                                    <span class="lyp-col-label font-medium text-gray-950 dark:text-white truncate text-sm" x-text="'Col ' + (colIndex + 1) + ' · ' + getColSpan(col) + '/12'"></span>
                                                    <div class="lyp-actions">
                                                        <button
                                                                @click.stop="columnMove(row.id, col.id, 'left')" :disabled="colIndex === 0" class="lyp-action-btn lyp-action-btn--sm" title="{{ __('layup::builder.move_left') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                                                        </button>
                                                        <button type="button" @click.stop="columnMove(row.id, col.id, 'right')" :disabled="colIndex === row.columns.length - 1" class="lyp-action-btn lyp-action-btn--sm" title="{{ __('layup::builder.move_right') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                                        </button>
                                                        <button type="button" @click.stop="columnEdit(row.id, col.id)" class="lyp-action-btn lyp-action-btn--sm" title="{{ __('layup::builder.column_settings') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        </button>
                                                        <button type="button" @click.stop="columnDelete(row.id, col.id)" class="lyp-action-btn lyp-action-btn--sm lyp-action-btn--danger" title="{{ __('layup::builder.delete_column') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                {{-- Widgets --}}
                                                <div class="lyp-widgets">
                                                    <template x-for="(widget, widgetIndex) in col.widgets" :key="widget.id">
                                                        <div>
                                                            <div
                                                                    class="lyp-drop-indicator"
                                                                    :class="{ 'lyp-drop-indicator--active': drag.dropTarget?.rowId === row.id && drag.dropTarget?.colId === col.id && drag.dropTarget?.position === widgetIndex }"
                                                            ></div>
                                                            <div
                                                                    class="lyp-widget"
                                                                    :class="{ 'lyp-widget--dragging': drag.active && drag.widgetId === widget.id }"
                                                                    draggable="true"
                                                                    @dragstart="onDragStart($event, row.id, col.id, widget.id, widgetIndex)"
                                                                    @dragend="onDragEnd()"
                                                                    @dragover.prevent.stop="onDragOverWidget($event, row.id, col.id, widgetIndex)"
                                                                    @click.stop="widgetEdit(row.id, col.id, widget.id)"
                                                            >
                                                                <div class="lyp-widget-header">
                                                                    <div style="display:flex;align-items:center;gap:0.375rem">
                                                                        <span class="lyp-drag-handle" title="{{ __('layup::builder.drag_to_reorder') }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5"/></svg>
                                                                        </span>
                                                                        <span class="lyp-widget-type" x-text="getWidgetLabel(widget.type)"></span>
                                                                    </div>
                                                                    <div class="lyp-actions">
                                                                        <button type="button" @click.stop="widgetEdit(row.id, col.id, widget.id)" class="lyp-action-btn lyp-action-btn--sm" title="{{ __('layup::builder.edit') }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                                                                        </button>
                                                                        <button type="button" @click.stop="widgetDuplicate(row.id, col.id, widget.id)" class="lyp-action-btn lyp-action-btn--sm" title="{{ __('layup::builder.duplicate') }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
                                                                        </button>
                                                                        <button type="button" @click.stop="widgetDelete(row.id, col.id, widget.id)" class="lyp-action-btn lyp-action-btn--sm lyp-action-btn--danger" title="{{ __('layup::builder.delete') }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                        class="lyp-widget-preview"
                                                                        :class="{ 'lyp-widget-preview--editable': isInlineEditable(widget.type) }"
                                                                        x-text="getWidgetPreview(widget)"
                                                                        @dblclick.stop="startInlineEdit(row.id, col.id, widget.id, widget.type, widget.data)"
                                                                ></div>
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <div
                                                            class="lyp-drop-indicator"
                                                            :class="{ 'lyp-drop-indicator--active': drag.dropTarget?.rowId === row.id && drag.dropTarget?.colId === col.id && drag.dropTarget?.position === col.widgets.length }"
                                                    ></div>
                                                </div>

                                                {{-- Add Widget --}}
                                                <button type="button" @click.stop="openPicker(row.id, col.id)" class="lyp-add-widget">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                                    {{ __('layup::builder.add_widget') }}
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Insert zone after row --}}
                            <div class="lyp-insert-zone" x-data="{ showTemplates: false }" @mouseenter="$el.classList.add('lyp-insert-zone--hover')" @mouseleave="if(!showTemplates) $el.classList.remove('lyp-insert-zone--hover')">
                                <div class="lyp-insert-line">
                                    <button type="button" @click.stop="showTemplates = !showTemplates" class="lyp-insert-btn" title="{{ __('layup::builder.add_row') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                    </button>
                                </div>
                                <div x-show="showTemplates" @click.away="showTemplates = false; $el.closest('.lyp-insert-zone').classList.remove('lyp-insert-zone--hover')" x-transition class="lyp-templates lyp-templates--inline">
                                    <p>{{ __('layup::builder.choose_layout') }}</p>
                                    <div class="lyp-templates-grid">
                                        <template x-for="(template, idx) in rowTemplates" :key="idx">
                                            <button type="button" @click="rowAddAt(template, rowIndex + 1); showTemplates = false; $el.closest('.lyp-insert-zone').classList.remove('lyp-insert-zone--hover')" class="lyp-tpl-btn">
                                                <template x-for="(span, ci) in template" :key="ci">
                                                    <div class="lyp-tpl-col" :style="'flex:' + span + ' ' + span + ' 0%'"></div>
                                                </template>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Drop indicator after last row --}}
                    <div
                            class="lyp-row-drop-indicator"
                            :class="{ 'lyp-row-drop-indicator--active': rowDrag.dropIndex === content.rows.length }"
                            @dragover.prevent="if(rowDrag.active) rowDrag.dropIndex = content.rows.length"
                            @drop.prevent="onRowDrop($event)"
                            style="min-height: 0.5rem"
                    ></div>

                    {{-- Add Row --}}
                    <div class="lyp-add-row-bottom" x-data="{
                            showTemplates: false,

                         }">
                        <button type="button" @click.stop="showTemplates = !showTemplates" class="lyp-add-row-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            {{ __('layup::builder.add_row_label') }}
                        </button>

                        <div x-show="showTemplates" @click.away="showTemplates = false" x-transition class="lyp-templates lyp-templates--bottom">
                            <p>{{ __('layup::builder.choose_layout') }}</p>
                            <div class="lyp-templates-grid">


                                <template x-for="(template, idx) in rowTemplates" :key="idx">
                                    <button type="button"
                                            @click="rowAdd(template); showTemplates = false" class="lyp-tpl-btn">
                                        <template x-for="(span, ci) in template" :key="ci">
                                            <div class="lyp-tpl-col" :style="'flex:' + span + ' ' + span + ' 0%'"></div>
                                        </template>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Empty State --}}
                    <template x-if="!content.rows || content.rows.length === 0">
                        <div class="lyp-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                            <p>{!! __('layup::builder.empty_state') !!}</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Action mount points (hidden — triggered via JS $wire.mountAction) --}}
        <div style="display:none !important; position:absolute; overflow:hidden; width:0; height:0;">
            {{ $getAction('rowDelete') }}
            {{ $getAction('rowEdit') }}
            {{ $getAction('columnDelete') }}
            {{ $getAction('columnEdit') }}
            {{ $getAction('widgetEdit') }}
            {{ $getAction('widgetDelete') }}
        </div>
        @include('layup::forms.modal-widget-picker')

    </div>

    @script
    <script>
        Alpine.data('layupBuilder', (config) => ({
            state: config.state,
            statePath: config.statePath,
            componentKey: config.componentKey,
            content: config.content,
            breakpoints: config.breakpoints,
            currentBreakpoint: config.defaultBreakpoint,
            rowTemplates: config.rowTemplates,
            widgetRegistry: config.widgetRegistry,
            translations: config.translations,
            showRuler: true,
            saving: false,

            // Widget Picker
            picker: {open: false, rowId: null, colId: null, search: ''},

            // Inline editing
            inlineEdit: {
                active: false,
                rowId: null,
                colId: null,
                widgetId: null,
                widgetType: null,
                originalData: null
            },

            // Column resizing
            columnResize: {active: false, rowId: null, colIndex: null, startX: 0, startSpans: []},

            selected: null,

            openPicker(rowId, colId) {
                this.picker = {open: true, rowId, colId, search: ''};
                this.$nextTick(() => {
                    this.$refs.pickerSearch?.focus();
                });
            },

            closePicker() {
                this.picker = {open: false, rowId: null, colId: null, search: ''};
            },

            selectWidget(type) {
                this.widgetAdd(this.picker.rowId, this.picker.colId, type);
                this.trackRecentWidget(type);
                this.closePicker();
            },

            trackRecentWidget(type) {
                try {
                    const recent = JSON.parse(localStorage.getItem('layup-recent-widgets') || '[]');
                    const filtered = recent.filter(t => t !== type);
                    filtered.unshift(type);
                    localStorage.setItem('layup-recent-widgets', JSON.stringify(filtered.slice(0, 5)));
                } catch (e) {
                    console.warn('Failed to track recent widget:', e);
                }
            },

            getRecentWidgets() {
                try {
                    const recent = JSON.parse(localStorage.getItem('layup-recent-widgets') || '[]');
                    return recent.map(type => {
                        const widget = this.widgetRegistry.find(w => w.type === type);
                        return widget || null;
                    }).filter(w => w !== null);
                } catch (e) {
                    return [];
                }
            },

            getFilteredWidgetCategories() {
                const q = this.picker.search.toLowerCase().trim();
                const cats = this.getWidgetCategories();
                if (!q) return cats;
                return cats.map(cat => ({
                    ...cat,
                    widgets: cat.widgets.filter(w => w.label.toLowerCase().includes(q) || w.type.toLowerCase().includes(q))
                })).filter(cat => cat.widgets.length > 0);
            },

            // Undo/Redo
            history: [],
            historyIndex: -1,
            maxHistory: 50,

            init() {
                if (!(this.content && typeof this.content === 'object')) {
                    this.content = {};
                }

                if (!(this.content.rows && typeof this.content.rows === 'object')) {
                    this.content.rows = [];
                }

                this.history = [JSON.parse(JSON.stringify(this.content))];
                this.historyIndex = 0;

                // Watch for Livewire saves
                Livewire.hook('request', ({respond}) => {
                    this.saving = true;
                    respond(() => {
                        setTimeout(() => {
                            this.saving = false;
                        }, 400);
                    });
                });

                let $self = this;

                window.addEventListener('layup-row-deleted', (event) => {
                    if (event.detail.statePath != $self.statePath) {
                        return;
                    }
                    $self.rowDeleted(event.detail.id);
                });

                window.addEventListener('layup-row-added', (event) => {
                    if (event.detail.statePath != $self.statePath) {
                        return;
                    }
                    // Row was already added by the .then() callback in rowAddAt,
                    // but if added via the action (not callSchemaComponentMethod), sync here.
                    const row = event.detail.row;
                    if (row && row.id) {
                        const exists = ($self.content.rows || []).some(r => r.id === row.id);
                        if (!exists) {
                            $self.content.rows = $self.content.rows ?? [];
                            $self.content.rows.splice(row.order ?? $self.content.rows.length, 0, row);
                            $self.pushHistory();
                        }
                    }
                });

                window.addEventListener('layup-row-updated', (event) => {
                    if (event.detail.statePath != $self.statePath) {
                        return;
                    }
                    const id = event.detail.id;
                    const settings = event.detail.settings;
                    if (id && settings) {
                        $self.content.rows = ($self.content.rows || []).map(row => {
                            if (row.id === id) {
                                row.settings = settings;
                            }
                            return row;
                        });
                        $self.pushHistory();
                    }
                });

                window.addEventListener('layup-column-deleted', (event) => {
                    if (event.detail.statePath != $self.statePath) return;
                    const rowId = event.detail.rowId;
                    const columnId = event.detail.columnId;
                    const row = ($self.content.rows || []).find(r => r.id === rowId);
                    if (row) {
                        row.columns = (row.columns || []).filter(c => c.id !== columnId);
                        $self.pushHistory();
                    }
                });

                window.addEventListener('layup-widget-deleted', (event) => {
                    if (event.detail.statePath != $self.statePath) return;
                    const { rowId, columnId, widgetId } = event.detail;
                    $self.content.rows = ($self.content.rows || []).map(function(row) {
                        if (row.id === rowId) {
                            row.columns = (row.columns || []).map(function(col) {
                                if (col.id === columnId) {
                                    col.widgets = (col.widgets || []).filter(w => w.id !== widgetId);
                                }
                                return col;
                            });
                        }
                        return row;
                    });
                    $self.pushHistory();
                });

                window.addEventListener('layup-widget-updated', (event) => {
                    if (event.detail.statePath != $self.statePath) return;
                    const { rowId, columnId, widgetId, data } = event.detail;
                    $self.content.rows = ($self.content.rows || []).map(function(row) {
                        if (row.id === rowId) {
                            row.columns = (row.columns || []).map(function(col) {
                                if (col.id === columnId) {
                                    col.widgets = (col.widgets || []).map(function(widget) {
                                        if (widget.id === widgetId && data) {
                                            widget.data = data;
                                        }
                                        return widget;
                                    });
                                }
                                return col;
                            });
                        }
                        return row;
                    });
                    $self.pushHistory();
                });

                window.addEventListener('layup-column-updated', (event) => {
                    if (event.detail.statePath != $self.statePath) return;
                    const rowId = event.detail.rowId;
                    const columnId = event.detail.columnId;
                    const span = event.detail.span;
                    const settings = event.detail.settings;

                    $self.content.rows = ($self.content.rows || []).map(function(row) {
                        if (row.id === rowId) {
                            row.columns = (row.columns || []).map(function(col) {
                                if (col.id === columnId) {
                                    if (span) col.span = span;
                                    if (settings) col.settings = settings;
                                }
                                return col;
                            });
                        }
                        return row;
                    });
                    $self.pushHistory();
                });

            },

            pushHistory() {
                const snapshot = JSON.parse(JSON.stringify(this.content));
                this.history = this.history.slice(0, this.historyIndex + 1);
                this.history.push(snapshot);
                if (this.history.length > this.maxHistory) {
                    this.history.shift();
                } else {
                    this.historyIndex++;
                }
            },
            /**
             * Row Methods
             */

            /**
             * @param id
             */
            rowDelete: function(id) {
                $wire.mountAction('rowDelete', { id: id }, { schemaComponent: this.componentKey});
            },
            rowDeleted: function(id) {
                if (typeof this.content.rows != 'object') {
                    return;
                }
                this.content.rows = this.content.rows.filter(row => row.id !== id);
            },
            rowDuplicate: function(id) {
                let $self = this;

                $wire.callSchemaComponentMethod(this.componentKey, 'rowDuplicate', {id: id})
                    .then(function (response) {
                        if (typeof response != 'object') {
                            // Add in a warning eventually..
                            return;
                        }

                        if (typeof response.id != 'string') {
                            // Add in a warning eventually..
                            return;
                        }

                        $self.content.rows = $self.content.rows ?? [];
                        $self.content.rows.splice(response.order, 0, response);
                    });

            },
            rowAddAt: function(schema, position) {
                let $self = this;

                // Ensure schema is always an array
                if (!Array.isArray(schema)) {
                    schema = [schema];
                }

                if (isNaN(position)) {
                    position = 0;
                }

                $wire.callSchemaComponentMethod(this.componentKey, 'rowAdd', {columns: schema, position: position})
                    .then(function (response) {

                        if (typeof response != 'object' || !response) {
                            return;
                        }

                        if (typeof response.id != 'string') {
                            return;
                        }

                        $self.content.rows = typeof $self.content.rows == 'object' ? $self.content.rows : [];
                        $self.content.rows.splice(response.order, 0, response);


                        $self.pushHistory();
                    });
            },

            rowAdd: function(schema) {
                this.content.rows = this.content.rows ?? [];
                this.rowAddAt(schema, this.content.rows.length);
            },

            rowEdit: function(id) {
                $wire.mountAction('rowEdit', { id: id }, { schemaComponent: this.componentKey});
            },

            /**
             * Column Methods
             */
            columnAdd: function(rowId) {
                let $self = this;
                $wire.callSchemaComponentMethod(this.componentKey, 'columnAdd', {rowId: rowId})
                    .then(function (response) {
                        if (typeof response != 'object' || !response.id) return;
                        $self.content.rows = ($self.content.rows || []).map(function(row) {
                            if (row.id === rowId) {
                                row.columns = [...(row.columns || []), response];
                            }
                            return row;
                        });
                        $self.pushHistory();
                    });
            },

            columnEdit: function(rowId, columnId) {
                $wire.mountAction('columnEdit', { rowId: rowId, columnId: columnId }, { schemaComponent: this.componentKey});
            },

            columnDelete: function(rowId, columnId) {
                $wire.mountAction('columnDelete', { rowId: rowId, columnId: columnId }, { schemaComponent: this.componentKey});
            },

            columnMove: function(rowId, columnId, direction) {
                let $self = this;
                // Optimistic local move
                const row = ($self.content.rows || []).find(r => r.id === rowId);
                if (row) {
                    const cols = row.columns || [];
                    const index = cols.findIndex(c => c.id === columnId);
                    if (index === -1) return;
                    const newIndex = direction === 'left' ? index - 1 : index + 1;
                    if (newIndex < 0 || newIndex >= cols.length) return;
                    [cols[index], cols[newIndex]] = [cols[newIndex], cols[index]];
                    row.columns = [...cols];
                    $self.pushHistory();
                }
                // Sync to PHP
                $wire.callSchemaComponentMethod(this.componentKey, 'columnMove', {rowId: rowId, columnId: columnId, direction: direction});
            },

            /**
             * Widget Methods
             */
            widgetEdit: function(rowId, columnId, widgetId) {
                $wire.mountAction('widgetEdit', { rowId: rowId, columnId: columnId, widgetId: widgetId }, { schemaComponent: this.componentKey});
            },

            widgetDelete: function(rowId, columnId, widgetId) {
                $wire.mountAction('widgetDelete', { rowId: rowId, columnId: columnId, widgetId: widgetId }, { schemaComponent: this.componentKey});
            },

            widgetAdd: function(rowId, columnId, widgetType, position) {
                let $self = this;
                let args = {rowId: rowId, columnId: columnId, widgetType: widgetType};
                if (typeof position === 'number') {
                    args.position = position;
                }
                $wire.callSchemaComponentMethod(this.componentKey, 'widgetAdd', args)
                    .then(function (response) {
                        if (typeof response != 'object' || !response.id) return;
                        $self.content.rows = ($self.content.rows || []).map(function(row) {
                            if (row.id === rowId) {
                                row.columns = (row.columns || []).map(function(col) {
                                    if (col.id === columnId) {
                                        let widgets = [...(col.widgets || [])];
                                        if (typeof position === 'number' && position >= 0) {
                                            widgets.splice(position, 0, response);
                                        } else {
                                            widgets.push(response);
                                        }
                                        col.widgets = widgets;
                                    }
                                    return col;
                                });
                            }
                            return row;
                        });
                        $self.pushHistory();
                    });
            },

            widgetDuplicate: function(rowId, columnId, widgetId) {
                let $self = this;
                $wire.callSchemaComponentMethod(this.componentKey, 'widgetDuplicate', {rowId: rowId, columnId: columnId, widgetId: widgetId})
                    .then(function (response) {
                        if (typeof response != 'object' || !response.id) return;
                        $self.content.rows = ($self.content.rows || []).map(function(row) {
                            if (row.id === rowId) {
                                row.columns = (row.columns || []).map(function(col) {
                                    if (col.id === columnId) {
                                        let widgets = [...(col.widgets || [])];
                                        const idx = widgets.findIndex(w => w.id === widgetId);
                                        if (idx !== -1) {
                                            widgets.splice(idx + 1, 0, response);
                                        } else {
                                            widgets.push(response);
                                        }
                                        col.widgets = widgets;
                                    }
                                    return col;
                                });
                            }
                            return row;
                        });
                        $self.pushHistory();
                    });
            },

            widgetMoveTo: function(sourceRowId, sourceColId, widgetId, targetRowId, targetColId, position) {
                let $self = this;
                // Optimistic local move
                let widget = null;
                $self.content.rows = ($self.content.rows || []).map(function(row) {
                    if (row.id === sourceRowId) {
                        row.columns = (row.columns || []).map(function(col) {
                            if (col.id === sourceColId) {
                                const idx = (col.widgets || []).findIndex(w => w.id === widgetId);
                                if (idx !== -1) {
                                    widget = col.widgets[idx];
                                    let widgets = [...col.widgets];
                                    widgets.splice(idx, 1);
                                    col.widgets = widgets;
                                }
                            }
                            return col;
                        });
                    }
                    return row;
                });
                if (widget) {
                    $self.content.rows = ($self.content.rows || []).map(function(row) {
                        if (row.id === targetRowId) {
                            row.columns = (row.columns || []).map(function(col) {
                                if (col.id === targetColId) {
                                    let widgets = [...(col.widgets || [])];
                                    widgets.splice(position, 0, widget);
                                    col.widgets = widgets;
                                }
                                return col;
                            });
                        }
                        return row;
                    });
                    $self.pushHistory();
                }
                // Sync to PHP
                $wire.callSchemaComponentMethod(this.componentKey, 'widgetMoveTo', {
                    sourceRowId: sourceRowId, sourceColId: sourceColId, widgetId: widgetId,
                    targetRowId: targetRowId, targetColId: targetColId, position: position
                });
            },

            undo() {
                if (this.historyIndex <= 0) return;
                if (this.historyIndex === this.history.length - 1) {
                    this.history.push(JSON.parse(JSON.stringify(this.content)));
                }
                this.historyIndex--;
                const state = JSON.parse(JSON.stringify(this.history[this.historyIndex]));
                this.content = state;
                $wire.restoreContent(state);
            },

            redo() {
                if (this.historyIndex >= this.history.length - 1) return;
                this.historyIndex++;
                const state = JSON.parse(JSON.stringify(this.history[this.historyIndex]));
                this.content = state;
                $wire.restoreContent(state);
            },

            onKeyDown(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) { e.preventDefault(); this.undo(); }
                if ((e.ctrlKey || e.metaKey) && e.key === 'z' && e.shiftKey) { e.preventDefault(); this.redo(); }
                if ((e.ctrlKey || e.metaKey) && e.key === 'y') { e.preventDefault(); this.redo(); }
            },

            // Row drag
            rowDrag: { active: false, rowId: null, sourceIndex: null, dropIndex: null },

            onRowDragStart(e, rowId, index) {
                this.rowDrag = { active: true, rowId, sourceIndex: index, dropIndex: null };
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', 'row:' + rowId);
            },
            onRowDragEnd() { this.rowDrag = { active: false, rowId: null, sourceIndex: null, dropIndex: null }; },
            onRowDragOver(e, rowIndex) {
                if (!this.rowDrag.active) return;
                const rect = e.currentTarget.getBoundingClientRect();
                const position = e.clientY < rect.top + rect.height / 2 ? rowIndex : rowIndex + 1;
                if (position === this.rowDrag.sourceIndex || position === this.rowDrag.sourceIndex + 1) { this.rowDrag.dropIndex = null; return; }
                this.rowDrag.dropIndex = position;
            },
            onRowDrop(e) {
                if (!this.rowDrag.active || this.rowDrag.dropIndex === null) return;
                let targetIndex = this.rowDrag.dropIndex;
                if (this.rowDrag.sourceIndex < targetIndex) targetIndex--;
                const sourceIndex = this.rowDrag.sourceIndex;
                const rowId = this.rowDrag.rowId;
                this.onRowDragEnd();

                if (sourceIndex !== targetIndex) {
                    // Optimistic local reorder
                    const rows = [...this.content.rows];
                    const [moved] = rows.splice(sourceIndex, 1);
                    rows.splice(targetIndex, 0, moved);
                    this.content.rows = rows;
                    this.pushHistory();

                    // Sync to PHP
                    $wire.callSchemaComponentMethod(this.componentKey, 'rowMoveTo', {id: rowId, targetIndex: targetIndex});
                }
            },

            // Widget drag
            drag: { active: false, widgetId: null, sourceRowId: null, sourceColId: null, sourceIndex: null, dropTarget: null, fromPicker: false, widgetType: null },

            onDragStart(e, rowId, colId, widgetId, index) {
                this.drag = { active: true, widgetId, sourceRowId: rowId, sourceColId: colId, sourceIndex: index, dropTarget: null, fromPicker: false, widgetType: null };
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', widgetId);
            },
            onDragEnd() { this.drag = { active: false, widgetId: null, sourceRowId: null, sourceColId: null, sourceIndex: null, dropTarget: null, fromPicker: false, widgetType: null }; },

            // Picker drag
            onPickerDragStart(e, widgetType) {
                this.drag = { active: true, widgetId: null, sourceRowId: null, sourceColId: null, sourceIndex: null, dropTarget: null, fromPicker: true, widgetType };
                e.dataTransfer.effectAllowed = 'copy';
                e.dataTransfer.setData('text/plain', 'picker:' + widgetType);
            },
            onPickerDragEnd() {
                this.onDragEnd();
            },
            onDragOverWidget(e, rowId, colId, widgetIndex) {
                if (!this.drag.active) return;
                const rect = e.currentTarget.getBoundingClientRect();
                const position = e.clientY < rect.top + rect.height / 2 ? widgetIndex : widgetIndex + 1;
                if (rowId === this.drag.sourceRowId && colId === this.drag.sourceColId && (position === this.drag.sourceIndex || position === this.drag.sourceIndex + 1)) { this.drag.dropTarget = null; return; }
                this.drag.dropTarget = { rowId, colId, position };
            },
            onDragOverCol(e, rowId, colId) {
                if (!this.drag.active) return;
                const col = this.findCol(rowId, colId);
                if (col && (col.widgets.length === 0 || this.drag.fromPicker)) {
                    this.drag.dropTarget = { rowId, colId, position: col.widgets.length };
                }
            },
            onDragLeaveCol(e) { if (!e.currentTarget.contains(e.relatedTarget)) this.drag.dropTarget = null; },
            onDropCol(e, rowId, colId) {
                if (!this.drag.active || !this.drag.dropTarget) return;
                const dt = this.drag.dropTarget;

                if (this.drag.fromPicker) {
                    // Adding new widget from picker
                    const widgetType = this.drag.widgetType;
                    this.onDragEnd();
                    this.closePicker();
                    this.widgetAdd(dt.rowId, dt.colId, widgetType, dt.position);
                    this.trackRecentWidget(widgetType);
                } else {
                    // Moving existing widget
                    let position = dt.position;
                    if (this.drag.sourceRowId === dt.rowId && this.drag.sourceColId === dt.colId && this.drag.sourceIndex < position) position--;
                    const { sourceRowId, sourceColId, widgetId } = this.drag;
                    this.onDragEnd();
                    this.widgetMoveTo(sourceRowId, sourceColId, widgetId, dt.rowId, dt.colId, position);
                }
            },

            findCol(rowId, colId) {
                const row = this.content.rows.find(r => r.id === rowId);
                return row ? row.columns.find(c => c.id === colId) : null;
            },
            getColSpan(col) { return col.span?.[this.currentBreakpoint] ?? col.span?.lg ?? 6; },
            getWidgetLabel(type) { const w = this.widgetRegistry.find(r => r.type === type); return w ? w.label : type; },
            getWidgetCategories() {
                const cats = {};
                const order = ['content', 'media', 'interactive', 'layout', 'advanced'];
                this.widgetRegistry.forEach(w => {
                    const cat = w.category || 'content';
                    if (!cats[cat]) cats[cat] = { name: (this.translations.categories && this.translations.categories[cat]) || cat, widgets: [] };
                    cats[cat].widgets.push(w);
                });
                return order.filter(c => cats[c]).map(c => cats[c]).concat(Object.keys(cats).filter(c => !order.includes(c)).map(c => cats[c]));
            },
            getWidgetPreview(widget) {
                const data = widget.data || {};
                const type = widget.type || '';

                // Widget-specific previews
                switch (type) {
                    case 'heading':
                    case 'animated-heading':
                        const level = data.level || 'h2';
                        const text = this.stripHtml(data.content || '');
                        return text ? `${level.toUpperCase()}: ${text.substring(0, 50)}` : '(empty heading)';

                    case 'text':
                    case 'blockquote':
                        const content = this.stripHtml(data.content || '');
                        return content ? content.substring(0, 60) + (content.length > 60 ? '…' : '') : '(empty text)';

                    case 'image':
                        const imgSrc = data.src || data.url || '';
                        const imgAlt = data.alt || '';
                        return imgSrc ? `🖼 ${imgAlt || imgSrc.split('/').pop()}` : '(no image)';

                    case 'video':
                        const vidSrc = data.src || data.url || '';
                        return vidSrc ? `🎬 ${vidSrc.split('/').pop()}` : '(no video)';

                    case 'button':
                    case 'cta':
                        const btnText = data.text || data.label || data.button_text || '';
                        const btnUrl = data.url || data.link || '';
                        return btnText ? `🔘 ${btnText}` : btnUrl ? `🔘 ${btnUrl}` : '(empty button)';

                    case 'blurb':
                        const blurbTitle = data.title || '';
                        const blurbText = this.stripHtml(data.content || data.text || '');
                        return blurbTitle ? `💡 ${blurbTitle}` : blurbText ? blurbText.substring(0, 50) : '(empty blurb)';

                    case 'icon-box':
                    case 'feature':
                        const featureTitle = data.title || data.heading || '';
                        return featureTitle ? `✨ ${featureTitle}` : '(empty feature)';

                    case 'testimonial':
                        const author = data.author || data.name || '';
                        const quote = this.stripHtml(data.content || data.quote || '');
                        return author ? `💬 ${author}` : quote ? `💬 ${quote.substring(0, 40)}` : '(empty testimonial)';

                    case 'accordion':
                    case 'tabs':
                        const items = data.items || [];
                        return items.length ? `📋 ${items.length} item${items.length !== 1 ? 's' : ''}` : '(no items)';

                    case 'gallery':
                        const images = data.images || [];
                        return images.length ? `🖼 ${images.length} image${images.length !== 1 ? 's' : ''}` : '(no images)';

                    case 'pricing-table':
                        const planName = data.name || data.title || '';
                        const price = data.price || '';
                        return planName ? `💰 ${planName}${price ? ' - ' + price : ''}` : '(empty plan)';

                    case 'countdown':
                        const targetDate = data.target_date || data.date || '';
                        return targetDate ? `⏱ ${targetDate}` : '(no date set)';

                    case 'map':
                        const address = data.address || data.location || '';
                        return address ? `📍 ${address}` : '(no location)';

                    case 'contact-form':
                    case 'newsletter':
                        const action = data.action || '';
                        return action ? `📧 → ${action}` : '(no action URL)';

                    case 'divider':
                        const dividerStyle = data.style || 'solid';
                        return `─── ${dividerStyle} ───`;

                    case 'spacer':
                        const height = data.height || '50px';
                        return `↕ ${height}`;

                    case 'code':
                    case 'html':
                        const codeSnippet = (data.content || data.code || '').substring(0, 40);
                        return codeSnippet ? `<> ${codeSnippet}…` : '(empty code)';

                    case 'social-follow':
                        const links = data.links || [];
                        return links.length ? `🔗 ${links.length} social link${links.length !== 1 ? 's' : ''}` : '(no links)';
                }

                // Fallback to generic preview
                if (data.content) {
                    const text = this.stripHtml(data.content);
                    return text.substring(0, 60) + (text.length > 60 ? '…' : '');
                }
                if (data.label || data.title || data.heading) {
                    return data.label || data.title || data.heading;
                }
                if (data.src || data.url) {
                    return '🖼 ' + (data.src || data.url);
                }
                return '(empty)';
            },

            stripHtml(html) {
                const tmp = document.createElement('div');
                tmp.innerHTML = html || '';
                return (tmp.textContent || tmp.innerText || '').trim();
            },

            // Inline editing
            isInlineEditable(widgetType) {
                return ['text', 'heading', 'animated-heading', 'blockquote'].includes(widgetType);
            },

            startInlineEdit(rowId, colId, widgetId, widgetType, data) {
                if (!this.isInlineEditable(widgetType)) return;

                this.inlineEdit = {
                    active: true,
                    rowId,
                    colId,
                    widgetId,
                    widgetType,
                    originalData: JSON.parse(JSON.stringify(data))
                };

                // Show prompt for inline edit
                const currentContent = this.stripHtml(data.content || '');
                const prompt = widgetType === 'heading' ? 'Edit heading:' : 'Edit text:';
                const newContent = window.prompt(prompt, currentContent);

                if (newContent !== null && newContent !== currentContent) {
                    this.saveInlineEdit(newContent);
                } else {
                    this.cancelInlineEdit();
                }
            },

            saveInlineEdit(newContent) {
                if (!this.inlineEdit.active) return;

                const { rowId, colId, widgetId } = this.inlineEdit;

                // Update widget data in content
                for (let row of this.content.rows) {
                    if (row.id !== rowId) continue;
                    for (let col of row.columns) {
                        if (col.id !== colId) continue;
                        for (let widget of col.widgets) {
                            if (widget.id === widgetId) {
                                widget.data.content = newContent;
                                break;
                            }
                        }
                    }
                }

                console.log('incoming update.');
                $wire.callSchemaComponentMethod(this.componentKey, 'widgetUpdateContent', {rowId: rowId, columnId: colId, widgetId: widgetId, content: newContent});
                this.cancelInlineEdit();
            },

            cancelInlineEdit() {
                this.inlineEdit = { active: false, rowId: null, colId: null, widgetId: null, widgetType: null, originalData: null };
            },

            // Column resizing
            startColumnResize(rowId, colIndex, event) {
                const row = this.content.rows.find(r => r.id === rowId);
                if (!row || colIndex <= 0 || colIndex >= row.columns.length) return;

                this.columnResize = {
                    active: true,
                    rowId,
                    colIndex,
                    startX: event.clientX,
                    startSpans: row.columns.map(col => this.getColSpan(col))
                };

                document.addEventListener('mousemove', this.onColumnResizeMove.bind(this));
                document.addEventListener('mouseup', this.onColumnResizeEnd.bind(this));
                document.body.style.cursor = 'col-resize';
                document.body.style.userSelect = 'none';
            },

            onColumnResizeMove(event) {
                if (!this.columnResize.active) return;

                const deltaX = event.clientX - this.columnResize.startX;
                const gridWidth = 12;
                const sensitivity = 50; // pixels per grid unit
                const deltaSpan = Math.round(deltaX / sensitivity);

                if (deltaSpan === 0) return;

                const { rowId, colIndex, startSpans } = this.columnResize;
                const row = this.content.rows.find(r => r.id === rowId);
                if (!row) return;

                const leftCol = row.columns[colIndex - 1];
                const rightCol = row.columns[colIndex];

                const newLeftSpan = Math.max(1, Math.min(11, startSpans[colIndex - 1] + deltaSpan));
                const newRightSpan = Math.max(1, Math.min(11, startSpans[colIndex] - deltaSpan));

                // Ensure total doesn't exceed 12
                if (newLeftSpan + newRightSpan <= gridWidth) {
                    const bp = this.currentBreakpoint;
                    leftCol.span = leftCol.span || {};
                    rightCol.span = rightCol.span || {};
                    leftCol.span[bp] = newLeftSpan;
                    rightCol.span[bp] = newRightSpan;
                }
            },

            onColumnResizeEnd() {
                if (!this.columnResize.active) return;

                document.removeEventListener('mousemove', this.onColumnResizeMove.bind(this));
                document.removeEventListener('mouseup', this.onColumnResizeEnd.bind(this));
                document.body.style.cursor = '';
                document.body.style.userSelect = '';

                // Save column span changes to PHP state
                if (this.columnResize.rowId) {
                    const row = this.content.rows.find(r => r.id === this.columnResize.rowId);
                    if (row) {
                        const spans = row.columns.map(col => col.span);
                        $wire.callSchemaComponentMethod(this.componentKey, 'columnResize', {
                            rowId: this.columnResize.rowId,
                            spans: spans
                        });
                    }
                }

                this.columnResize = { active: false, rowId: null, colIndex: null, startX: 0, startSpans: [] };
            },

            getIconSvg(iconName) {
                const icons = {
                    'heroicon-o-puzzle-piece': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.39 48.39 0 01-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 01-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 00-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 01-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 00.657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 01-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 005.427-.63 48.05 48.05 0 00.582-4.717.532.532 0 00-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 00.658-.663 48.422 48.422 0 00-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 01-.61-.58v0z"/></svg>',
                    'heroicon-o-photo': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>',
                    'heroicon-o-document-text': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>',
                    'heroicon-o-play-circle': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z"/></svg>',
                    'heroicon-o-h1': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.243 4.493v7.5m0 0v7.502m0-7.501h10.5m0-7.5v7.5m0 0v7.501m4.5-8.909 2.25-1.5v10.409"/></svg>',
                    'heroicon-o-code-bracket': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/></svg>',
                    'heroicon-o-light-bulb': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>',
                    'heroicon-o-envelope': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>',
                    'heroicon-o-map-pin': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>',
                    'heroicon-o-users': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>',
                    'heroicon-o-list-bullet': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>',
                    'heroicon-o-currency-dollar': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
                    'heroicon-o-tag': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>',
                    'heroicon-o-squares-plus': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 0 0 2.25-2.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v2.25A2.25 2.25 0 0 0 6 10.5Zm0 9.75h2.25A2.25 2.25 0 0 0 10.5 18v-2.25a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25V18A2.25 2.25 0 0 0 6 20.25Zm9.75-9.75H18a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 18 3.75h-2.25A2.25 2.25 0 0 0 13.5 6v2.25a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>',
                    'heroicon-o-megaphone': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46"/></svg>',
                    'heroicon-o-arrow-down-tray': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>',
                    'heroicon-o-newspaper': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/></svg>',
                    'heroicon-o-chart-bar-square': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z"/></svg>',
                    'heroicon-o-view-columns': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v13.5c0 .621.504 1.125 1.125 1.125Z"/></svg>',
                    'heroicon-o-chat-bubble-left-right': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>',
                    'heroicon-o-trophy': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"/></svg>',
                    'heroicon-o-adjustments-horizontal': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/></svg>',
                    'heroicon-o-bars-3': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>',
                    'heroicon-o-chat-bubble-bottom-center-text': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>',
                    'heroicon-o-star': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>',
                    'heroicon-o-play': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z"/></svg>',
                    'heroicon-o-musical-note': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 0 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z"/></svg>',
                    'heroicon-o-sparkles': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/></svg>',
                    'heroicon-o-clock': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
                    'heroicon-o-arrows-right-left': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>',
                    'heroicon-o-question-mark-circle': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/></svg>',
                    'heroicon-o-magnifying-glass': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>',
                    'heroicon-o-share': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/></svg>',
                    'heroicon-o-arrow-path': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>',
                    'heroicon-o-numbered-list': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.242 5.992h12m-12 6.003H20.24m-12 5.999h12M4.117 7.495v-3.75H2.99m1.125 3.75H2.99m1.125 0H5.24m-1.92 2.577a1.125 1.125 0 1 1 1.591 1.59l-1.83 1.83h2.16M2.99 15.745h1.125a1.125 1.125 0 0 1 0 2.25H3.74m0-.002h.375a1.125 1.125 0 0 1 0 2.25H2.99"/></svg>',
                    'heroicon-o-table-cells': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/></svg>',
                    'heroicon-o-squares-2x2': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>',
                    'heroicon-o-link': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>',
                    'heroicon-o-minus': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>',
                    'heroicon-o-rectangle-group': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z"/></svg>',
                    'heroicon-o-window': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8.25V18a2.25 2.25 0 0 0 2.25 2.25h13.5A2.25 2.25 0 0 0 21 18V8.25m-18 0V6a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 6v2.25m-18 0h18M5.25 6h.008v.008H5.25V6ZM7.5 6h.008v.008H7.5V6Zm2.25 0h.008v.008H9.75V6Z"/></svg>',
                    'heroicon-o-paint-brush': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m-5.043-.025a15.994 15.994 0 0 1 1.622-3.395m3.42 3.42a15.995 15.995 0 0 0 4.764-4.648l3.876-5.814a1.151 1.151 0 0 0-1.597-1.597L14.146 6.32a15.996 15.996 0 0 0-4.649 4.763m3.42 3.42a6.776 6.776 0 0 0-3.42-3.42"/></svg>',
                    'heroicon-o-cursor-arrow-rays': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672Zm-7.518-.267A8.25 8.25 0 1 1 20.25 10.5M8.288 14.212A5.25 5.25 0 1 1 17.25 10.5"/></svg>',
                    'heroicon-o-lock-closed': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>',
                    'heroicon-o-envelope-open': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 0 1-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 0 0 1.183 1.981l6.478 3.488m8.839 2.51-4.66-2.51m0 0-1.023-.55a2.25 2.25 0 0 0-2.134 0l-1.022.55m0 0-4.661 2.51m16.5 1.615a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V8.844a2.25 2.25 0 0 1 1.183-1.981l7.5-4.039a2.25 2.25 0 0 1 2.134 0l7.5 4.039a2.25 2.25 0 0 1 1.183 1.98V19.5Z"/></svg>',
                    'heroicon-o-check-circle': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
                    'heroicon-o-exclamation-triangle': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>',
                    'heroicon-o-bell-alert': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5"/></svg>',
                    'heroicon-o-globe-alt': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/></svg>',
                    'heroicon-o-user-circle': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>',
                    'heroicon-o-eye-slash': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>',
                    'heroicon-o-chat-bubble-left': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>',
                    'heroicon-o-user-group': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>',
                    'heroicon-o-building-office': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>',
                    'heroicon-o-building-office-2': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/></svg>',
                    'heroicon-o-presentation-chart-bar': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 5.25m8.5-5.25 1 5.25m-1-5.25H9m1.5-2.25v2.25m3-2.25v2.25"/></svg>',
                    'heroicon-o-chart-bar': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>',
                    'heroicon-o-scale': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z"/></svg>',
                    'heroicon-o-shield-check': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>',
                    'heroicon-o-arrow-up': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18"/></svg>',
                    'heroicon-o-arrow-right': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>',
                    'heroicon-o-chevron-right': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>',
                    'heroicon-o-chevron-down': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>',
                    'heroicon-o-arrows-up-down': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/></svg>',
                    'heroicon-o-bars-3-bottom-left': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>',
                    'heroicon-o-rectangle-stack': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122"/></svg>',
                    'heroicon-o-square-3-stack-3d': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3"/></svg>',
                };
                return icons[iconName] || icons['heroicon-o-puzzle-piece'];
            },
        }));
    </script>
    @endscript
</x-dynamic-component>
