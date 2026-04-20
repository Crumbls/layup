<?php

declare(strict_types=1);

use Crumbls\Layup\Support\WidgetRegistry;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;

/**
 * Extracts top-level field names from a form schema, skipping into
 * Repeater and Builder children (which are per-row sub-schemas, not
 * top-level widget data keys). Layout containers (Section, Grid, Group,
 * Fieldset, etc.) are still descended into because their children ARE
 * top-level sibling fields.
 *
 * @param  array<\Filament\Schemas\Components\Component>  $components
 * @return array<string>
 */
function extractTopLevelFieldNames(array $components): array
{
    $names = [];

    foreach ($components as $component) {
        if (method_exists($component, 'getName')) {
            $name = $component->getName();

            if ($name !== null && $name !== '') {
                $names[] = $name;
            }
        }

        // Repeater and Builder treat their children as per-row sub-schemas —
        // do not descend into them for top-level key extraction.
        if ($component instanceof Repeater || $component instanceof Builder) {
            continue;
        }

        try {
            $ref = new ReflectionProperty($component, 'childComponents');
            $children = $ref->getValue($component);

            foreach ($children as $group) {
                if (is_array($group)) {
                    $names = array_merge($names, extractTopLevelFieldNames($group));
                }
            }
        } catch (ReflectionException) {
            // Component has no childComponents property -- skip.
        }
    }

    return $names;
}

it('every registered widget has a default value for every top-level content form field', function (): void {
    $registry = app(WidgetRegistry::class);
    $gaps = [];

    foreach ($registry->all() as $type => $class) {
        $schema = $class::getContentFormSchema();
        $fieldNames = extractTopLevelFieldNames($schema);
        $defaults = array_keys($class::getDefaultData());
        $missing = array_diff($fieldNames, $defaults);

        foreach ($missing as $field) {
            $gaps[] = "{$class}: '{$field}'";
        }
    }

    expect($gaps)
        ->toBeEmpty(
            "The following widgets have top-level content form fields with no default value:\n  - " .
            implode("\n  - ", $gaps)
        );
});
