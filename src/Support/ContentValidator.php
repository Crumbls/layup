<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

class ContentValidator
{
    public function __construct(protected bool $strict = false) {}

    public function validate(mixed $content): ValidationResult
    {
        $errors = [];

        if (! is_array($content)) {
            return new ValidationResult(['Content must be an array.']);
        }

        if (! array_key_exists('rows', $content)) {
            return new ValidationResult(['Missing "rows" key.']);
        }

        if (! is_array($content['rows'])) {
            return new ValidationResult(['"rows" must be an array.']);
        }

        foreach ($content['rows'] as $ri => $row) {
            if (! is_array($row) || ! array_key_exists('columns', $row)) {
                $errors[] = "Row {$ri}: missing \"columns\" key.";

                continue;
            }

            if (! is_array($row['columns'])) {
                $errors[] = "Row {$ri}: \"columns\" must be an array.";

                continue;
            }

            foreach ($row['columns'] as $ci => $col) {
                if (! is_array($col) || ! array_key_exists('widgets', $col)) {
                    $errors[] = "Row {$ri}, Column {$ci}: missing \"widgets\" key.";

                    continue;
                }

                if (! is_array($col['widgets'])) {
                    $errors[] = "Row {$ri}, Column {$ci}: \"widgets\" must be an array.";

                    continue;
                }

                foreach ($col['widgets'] as $wi => $widget) {
                    if (! is_array($widget) || ! array_key_exists('type', $widget)) {
                        $errors[] = "Row {$ri}, Column {$ci}, Widget {$wi}: missing \"type\" key.";

                        continue;
                    }

                    if (! is_string($widget['type']) || $widget['type'] === '') {
                        $errors[] = "Row {$ri}, Column {$ci}, Widget {$wi}: \"type\" must be a non-empty string.";

                        continue;
                    }

                    if ($this->strict) {
                        $registry = app(WidgetRegistry::class);
                        if (! $registry->has($widget['type'])) {
                            $errors[] = "Row {$ri}, Column {$ci}, Widget {$wi}: unknown widget type \"{$widget['type']}\".";
                        }
                    }

                    // Validate widget data against required fields
                    $data = $widget['data'] ?? [];
                    $widgetWarnings = $this->validateWidgetData($widget['type'], $data);
                    foreach ($widgetWarnings as $warning) {
                        $errors[] = "Row {$ri}, Column {$ci}, Widget {$wi} ({$widget['type']}): {$warning}";
                    }
                }
            }
        }

        return new ValidationResult($errors);
    }

    /**
     * Validate widget-specific data requirements.
     *
     * @return array<string>
     */
    protected function validateWidgetData(string $type, array $data): array
    {
        $warnings = [];

        if (! $this->strict) {
            return $warnings;
        }

        // Try widget-declared rules first
        $registry = app(WidgetRegistry::class);
        $class = $registry->get($type);

        if ($class) {
            $rules = $class::getValidationRules();

            if ($rules !== []) {
                foreach ($rules as $field => $rule) {
                    if (str_contains($rule, 'required') && empty($data[$field])) {
                        $label = str_replace('_', ' ', $field);
                        $warnings[] = "missing required field \"{$label}\".";
                    }
                }

                return $warnings;
            }
        }

        // Fallback: legacy hardcoded rules for built-in widgets
        $requiredFields = match ($type) {
            'button' => ['label' => 'label', 'url' => 'URL'],
            'image' => ['src' => 'image source'],
            'video' => ['url' => 'video URL'],
            'audio' => ['src' => 'audio source'],
            'countdown' => ['target_date' => 'target date'],
            'map' => ['lat' => 'latitude', 'lng' => 'longitude'],
            'heading' => ['content' => 'content'],
            'text' => ['content' => 'content'],
            'rich-text' => ['content' => 'content'],
            'icon' => ['icon' => 'icon'],
            'embed' => ['html' => 'embed code'],
            'alert' => ['content' => 'content'],
            'contact-form' => ['email' => 'email address'],
            'newsletter' => ['action' => 'form action URL'],
            'file-download' => ['url' => 'download URL'],
            'lottie' => ['src' => 'animation source'],
            default => [],
        };

        foreach ($requiredFields as $field => $label) {
            if (empty($data[$field])) {
                $warnings[] = "missing required field \"{$label}\".";
            }
        }

        return $warnings;
    }
}
