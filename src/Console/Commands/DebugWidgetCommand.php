<?php

declare(strict_types=1);

namespace Crumbls\Layup\Console\Commands;

use Crumbls\Layup\Support\WidgetRegistry;
use Crumbls\Layup\View\BaseView;
use Illuminate\Console\Command;

class DebugWidgetCommand extends Command
{
    protected $signature = 'layup:debug-widget
        {type : Widget type identifier}
        {--data= : JSON data to pass to the widget}';

    protected $description = 'Dump the full resolved state of a widget for debugging';

    public function handle(): int
    {
        $type = $this->argument('type');
        $registry = app(WidgetRegistry::class);

        foreach (config('layup.widgets', []) as $widgetClass) {
            if (class_exists($widgetClass) && ! $registry->has($widgetClass::getType())) {
                $registry->register($widgetClass);
            }
        }

        $class = $registry->get($type);

        if (! $class) {
            $this->error("Widget type '{$type}' is not registered.");

            return self::FAILURE;
        }

        $inputData = [];
        if ($this->option('data')) {
            $inputData = json_decode($this->option('data'), true) ?? [];
        }

        $defaults = $class::getDefaultData();
        $merged = array_merge($defaults, $inputData);

        $this->info('Layup Widget Debug');
        $this->line(str_repeat('-', 40));
        $this->line("Type: {$type}");
        $this->line("Class: {$class}");
        $this->line("Category: {$class::getCategory()}");
        $this->line('Deprecated: ' . ($class::isDeprecated() ? 'Yes - ' . $class::getDeprecationMessage() : 'No'));

        // Form fields
        $fields = $this->extractFieldNames($class::getContentFormSchema());
        $this->line('Form fields (' . count($fields) . '): ' . ($fields !== [] ? implode(', ', $fields) : '(none)'));

        // Data
        $this->line('Default data: ' . json_encode($defaults, JSON_UNESCAPED_SLASHES));
        if ($inputData !== []) {
            $this->line('Input data: ' . json_encode($inputData, JSON_UNESCAPED_SLASHES));
        }
        $this->line('Merged data: ' . json_encode($merged, JSON_UNESCAPED_SLASHES));

        // Validation rules
        $rules = $class::getValidationRules();
        if ($rules !== []) {
            $ruleStr = implode(', ', array_map(fn ($k, $v) => "{$k}={$v}", array_keys($rules), $rules));
            $this->line("Validation rules: {$ruleStr}");
        } else {
            $this->line('Validation rules: (none)');
        }

        // Assets
        $assets = $class::getAssets();
        $jsCount = count($assets['js'] ?? []);
        $cssCount = count($assets['css'] ?? []);
        if ($jsCount > 0 || $cssCount > 0) {
            $parts = [];
            if ($jsCount > 0) {
                $parts[] = implode(', ', $assets['js']);
            }
            if ($cssCount > 0) {
                $parts[] = implode(', ', $assets['css']);
            }
            $this->line('Assets: ' . implode(', ', $parts));
        } else {
            $this->line('Assets: (none)');
        }

        // Search terms
        $terms = $class::getSearchTerms();
        $this->line('Search terms: ' . ($terms !== [] ? implode(', ', $terms) : '(none)'));

        // Preview
        $this->line('Preview: "' . $class::getPreview($merged) . '"');

        // prepareForRender
        $prepared = $class::prepareForRender($merged);
        if ($prepared !== $merged) {
            $this->line('prepareForRender: ' . json_encode($prepared, JSON_UNESCAPED_SLASHES));
        } else {
            $this->line('prepareForRender: (no transformation)');
        }

        // Rendered HTML
        try {
            $widget = $class::make($prepared);
            $html = $widget->render()->render();
            $bytes = strlen($html);
            $this->line("Rendered HTML ({$bytes} bytes):");
            $this->line('  ' . mb_substr(trim($html), 0, 500));
        } catch (\Throwable $e) {
            $this->error('Render failed: ' . $e->getMessage());
        }

        return self::SUCCESS;
    }

    /**
     * @return array<string>
     */
    protected function extractFieldNames(array $components): array
    {
        $names = [];

        BaseView::walkComponents($components, function ($component) use (&$names): void {
            if (method_exists($component, 'getName')) {
                $name = $component->getName();
                if ($name !== null && $name !== '') {
                    $names[] = $name;
                }
            }
        });

        return $names;
    }
}
