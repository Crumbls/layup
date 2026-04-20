<?php

declare(strict_types=1);

use Crumbls\Layup\Contracts\Widget;
use Crumbls\Layup\View\BaseWidget;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;

/**
 * Extract top-level field names from a form schema, skipping into
 * Repeater and Builder children. Those are per-row sub-schemas whose
 * defaults live inside the parent's default array, not as top-level
 * widget data keys. Layout containers (Section, Grid, Group, Fieldset,
 * etc.) are still descended into because their children are top-level
 * siblings grouped for presentation only.
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
        }
    }

    return $names;
}

/**
 * Discover every concrete Widget class shipped with the package by
 * scanning src/View/ directly. This sidesteps the need to boot a
 * Filament panel in the test environment.
 *
 * @return array<class-string<Widget>>
 */
function discoverShippedWidgets(): array
{
    $files = glob(__DIR__ . '/../../src/View/*Widget.php') ?: [];
    $classes = [];

    foreach ($files as $file) {
        $base = basename($file, '.php');

        if ($base === 'BaseWidget') {
            continue;
        }

        $class = 'Crumbls\\Layup\\View\\' . $base;

        if (! class_exists($class)) {
            continue;
        }

        $ref = new ReflectionClass($class);

        if ($ref->isAbstract() || ! $ref->isSubclassOf(BaseWidget::class)) {
            continue;
        }

        $classes[] = $class;
    }

    sort($classes);

    return $classes;
}

it('every shipped widget has a default for every top-level content form field', function (): void {
    $gaps = [];

    foreach (discoverShippedWidgets() as $class) {
        $fieldNames = extractTopLevelFieldNames($class::getContentFormSchema());
        $defaults = array_keys($class::getDefaultData());
        $missing = array_values(array_diff($fieldNames, $defaults));

        foreach ($missing as $field) {
            $gaps[] = "{$class}: '{$field}'";
        }
    }

    expect($gaps)->toBeEmpty(
        "The following widgets have top-level content form fields with no default value:\n  - " .
        implode("\n  - ", $gaps),
    );
});
