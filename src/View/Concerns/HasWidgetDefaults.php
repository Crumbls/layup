<?php

declare(strict_types=1);

namespace Crumbls\Layup\View\Concerns;

use Crumbls\Layup\Support\WidgetContext;

/**
 * Default implementations for the static portions of the Widget contract.
 *
 * Shared by both BaseBladeWidget and BaseLivewireWidget so that the
 * editor-side metadata (icon, category, defaults, lifecycle hooks, preview,
 * search terms, validation, deprecation, asset declarations, toArray)
 * lives in one place. Concrete widgets override only what they need.
 *
 * The two pieces left out of this trait are:
 *   - render() — varies by rendering tech (Blade view vs. Livewire mount)
 *   - getViewName() — only meaningful for Blade-rendered widgets
 *   - getType() / getLabel() — required per widget, no useful default
 *   - getContentFormSchema() — required per widget, default sits in BaseView
 */
trait HasWidgetDefaults
{
    public static function getIcon(): string
    {
        return 'heroicon-o-puzzle-piece';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getDefaultData(): array
    {
        return [];
    }

    /**
     * Generate preview text for the builder canvas.
     * Override in subclasses for richer previews.
     */
    public static function getPreview(array $data): string
    {
        if (! empty($data['content'])) {
            $text = strip_tags((string) $data['content']);

            return mb_strlen($text) > 60 ? mb_substr($text, 0, 60) . "\u{2026}" : $text;
        }

        if (! empty($data['label'])) {
            return $data['label'];
        }

        if (! empty($data['src'])) {
            return "\u{1F5BC} " . basename((string) $data['src']);
        }

        return '(empty)';
    }

    /**
     * Whether the builder canvas should render this widget's real HTML
     * as its preview, instead of the plain-text getPreview() string.
     * Override and return true for cheap, static widgets that look better
     * rendered in place (e.g. breadcrumbs, lists).
     */
    public static function supportsLivePreview(): bool
    {
        return false;
    }

    public static function prepareForRender(array $data): array
    {
        return $data;
    }

    /**
     * @return array<string, string>
     */
    public static function getValidationRules(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    public static function getSearchTerms(): array
    {
        return [];
    }

    public static function isDeprecated(): bool
    {
        return false;
    }

    public static function getDeprecationMessage(): string
    {
        return '';
    }

    /**
     * Called after save. Override to transform or validate data.
     * Context is provided when available (page, row/column/widget IDs).
     */
    public static function onSave(array $data, ?WidgetContext $context = null): array
    {
        return $data;
    }

    /**
     * Called on widget creation. Override for init logic.
     * Context is provided when available.
     */
    public static function onCreate(array $data, ?WidgetContext $context = null): array
    {
        return $data;
    }

    /**
     * Called on widget deletion. Override for cleanup.
     * Context is provided when available.
     */
    public static function onDelete(array $data, ?WidgetContext $context = null): void
    {
        // No-op by default
    }

    public static function onDuplicate(array $data, ?WidgetContext $context = null): array
    {
        return $data;
    }

    /**
     * @return array{js?: array<string>, css?: array<string>}
     */
    public static function getAssets(): array
    {
        return [];
    }

    public static function toArray(): array
    {
        return [
            'type' => static::getType(),
            'label' => static::getLabel(),
            'icon' => static::getIcon(),
            'category' => static::getCategory(),
            'defaults' => static::getDefaultData(),
            'search_terms' => static::getSearchTerms(),
            'deprecated' => static::isDeprecated(),
            'deprecation_message' => static::getDeprecationMessage(),
            'supports_live_preview' => static::supportsLivePreview(),
        ];
    }
}
