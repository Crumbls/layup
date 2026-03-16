<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

use Crumbls\Layup\Contracts\Widget;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

class WidgetRegistry
{
    /** @var array<string, class-string<Widget>> */
    protected array $widgets = [];

    /** @var string|null Cached fingerprint of currently registered widgets. */
    protected ?string $fingerprint = null;

    /**
     * Register a widget class. Must implement the Widget contract.
     */
    public function register(string $widgetClass): static
    {
        if (! is_subclass_of($widgetClass, Widget::class)) {
            throw new InvalidArgumentException(
                "{$widgetClass} must implement " . Widget::class
            );
        }

        $type = $widgetClass::getType();

        if (isset($this->widgets[$type])) {
            logger()->warning("Layup: Widget type '{$type}' already registered by {$this->widgets[$type]}. Overriding with {$widgetClass}.");
        }

        $this->widgets[$type] = $widgetClass;
        $this->fingerprint = null;

        return $this;
    }

    /**
     * Unregister a widget by type.
     */
    public function unregister(string $type): static
    {
        unset($this->widgets[$type]);
        $this->fingerprint = null;

        return $this;
    }

    /**
     * Get the widget class for a given type.
     */
    public function get(string $type): ?string
    {
        return $this->widgets[$type] ?? null;
    }

    /**
     * Check if a widget type is registered.
     */
    public function has(string $type): bool
    {
        return isset($this->widgets[$type]);
    }

    /**
     * Get all registered widget classes keyed by type.
     */
    public function all(): array
    {
        return $this->widgets;
    }

    /**
     * Return widget metadata for the Alpine.js builder.
     *
     * Results are cached using a fingerprint of the registered widget types,
     * so the cache auto-invalidates whenever widgets are added or removed.
     */
    public function toJs(): array
    {
        return Cache::remember(
            "layup.widget-js.{$this->getFingerprint()}",
            3600,
            fn (): array => collect($this->widgets)
                ->map(fn (string $class) => $class::toArray())
                ->values()
                ->all(),
        );
    }

    /**
     * Get the Filament form schema for a given widget type.
     */
    public function getFormSchema(string $type): array
    {
        $class = $this->get($type);

        return $class ? $class::getFormSchema() : [];
    }

    /**
     * Get default data for a given widget type.
     */
    public function getDefaultData(string $type): array
    {
        $class = $this->get($type);

        return $class ? $class::getDefaultData() : [];
    }

    /**
     * Get preview text for a widget's data.
     */
    public function getPreview(string $type, array $data): string
    {
        $class = $this->get($type);

        return $class ? $class::getPreview($data) : '(unknown widget)';
    }

    /**
     * Run the onCreate callback for a widget type.
     */
    public function fireOnCreate(string $type, array $data, ?WidgetContext $context = null): array
    {
        $class = $this->get($type);

        return $class ? $class::onCreate($data, $context) : $data;
    }

    /**
     * Run the onSave callback for a widget type.
     */
    public function fireOnSave(string $type, array $data, ?WidgetContext $context = null): array
    {
        $class = $this->get($type);

        return $class ? $class::onSave($data, $context) : $data;
    }

    /**
     * Run the onDelete callback for a widget type.
     */
    public function fireOnDelete(string $type, array $data, ?WidgetContext $context = null): void
    {
        $class = $this->get($type);
        if ($class) {
            $class::onDelete($data, $context);
        }
    }

    /**
     * Run the onDuplicate callback for a widget type.
     */
    public function fireOnDuplicate(string $type, array $data, ?WidgetContext $context = null): array
    {
        $class = $this->get($type);

        return $class ? $class::onDuplicate($data, $context) : $data;
    }

    /**
     * Get all widget types grouped by category.
     *
     * Results are cached using the same fingerprint strategy as toJs().
     */
    public function grouped(): array
    {
        return Cache::remember(
            "layup.widget-grouped.{$this->getFingerprint()}",
            3600,
            fn (): array => collect($this->widgets)
                ->mapToGroups(fn (string $class, string $type): array => [$class::getCategory() => $class::toArray()])
                ->all(),
        );
    }

    /**
     * Generate a fingerprint of the currently registered widgets.
     *
     * The fingerprint changes whenever widgets are registered or unregistered,
     * which naturally invalidates any cache entries keyed on the old fingerprint.
     * Stale cache entries are left to expire on their own TTL.
     */
    public function getFingerprint(): string
    {
        if ($this->fingerprint === null) {
            $types = array_keys($this->widgets);
            sort($types);
            $this->fingerprint = md5(implode(',', $types));
        }

        return $this->fingerprint;
    }

    /**
     * Explicitly clear all cached widget metadata.
     *
     * Not typically needed since the fingerprint strategy handles invalidation
     * automatically, but useful for commands or deployment scripts.
     */
    public function clearCache(): void
    {
        Cache::forget("layup.widget-js.{$this->getFingerprint()}");
        Cache::forget("layup.widget-grouped.{$this->getFingerprint()}");
        $this->fingerprint = null;
    }
}
