<?php

declare(strict_types=1);

namespace Crumbls\Layup\Contracts;

use Crumbls\Layup\Support\WidgetContext;

/**
 * Contract for all Layup page builder widgets.
 *
 * Implement this interface to create custom widgets. Each widget defines
 * its own editing form (Filament Schema), default data, preview text
 * for the builder canvas, and optional callbacks for lifecycle events.
 */
interface Widget
{
    /**
     * Unique string identifier for this widget type.
     * Used in JSON storage and widget registry lookups.
     */
    public static function getType(): string;

    /**
     * Human-readable label shown in the widget picker and builder canvas.
     */
    public static function getLabel(): string;

    /**
     * Heroicon name for UI display in the widget picker.
     */
    public static function getIcon(): string;

    /**
     * Category for grouping in the widget picker.
     * Core categories: 'content', 'media', 'layout', 'interactive', 'advanced'
     */
    public static function getCategory(): string;

    /**
     * Filament form schema components for editing this widget's data.
     *
     * @return array<\Filament\Forms\Components\Component>
     */
    public static function getFormSchema(): array;

    /**
     * Default data values when a new widget instance is created.
     */
    public static function getDefaultData(): array;

    /**
     * Generate a short plain-text preview for the builder canvas.
     * Receives the widget's stored data array.
     */
    public static function getPreview(array $data): string;

    /**
     * Transform stored data before it reaches the Blade view.
     * Called automatically in the render pipeline.
     */
    public static function prepareForRender(array $data): array;

    /**
     * Validation rules for this widget's data.
     * Keys are field names, values are Laravel validation rule strings.
     *
     * @return array<string, string>
     */
    public static function getValidationRules(): array;

    /**
     * Additional search terms for the builder's widget picker.
     *
     * @return array<string>
     */
    public static function getSearchTerms(): array;

    /**
     * Whether this widget is deprecated.
     */
    public static function isDeprecated(): bool;

    /**
     * Message explaining deprecation and migration path.
     */
    public static function getDeprecationMessage(): string;

    /**
     * Called after the widget data is saved via the slideover.
     * Context is provided when available (page, row/column/widget IDs).
     *
     * Return the (possibly modified) data array.
     */
    public static function onSave(array $data, ?WidgetContext $context = null): array;

    /**
     * Called when the widget is first created (added to a column).
     * Context is provided when available.
     *
     * Return the initial data array.
     */
    public static function onCreate(array $data, ?WidgetContext $context = null): array;

    /**
     * Called when the widget is deleted from a column.
     * Context is provided when available.
     */
    public static function onDelete(array $data, ?WidgetContext $context = null): void;

    /**
     * Called when the widget is duplicated within the builder.
     * Use to clone resources (e.g. copy uploaded files).
     *
     * Return the (possibly modified) data array for the duplicate.
     */
    public static function onDuplicate(array $data, ?WidgetContext $context = null): array;

    /**
     * Declare JS/CSS dependencies for this widget.
     *
     * @return array{js?: array<string>, css?: array<string>}
     */
    public static function getAssets(): array;

    /**
     * Serialize widget metadata for the Alpine.js builder.
     */
    public static function toArray(): array;
}
