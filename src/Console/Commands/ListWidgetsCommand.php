<?php

declare(strict_types=1);

namespace Crumbls\Layup\Console\Commands;

use Crumbls\Layup\Support\WidgetRegistry;
use Illuminate\Console\Command;

class ListWidgetsCommand extends Command
{
    protected $signature = 'layup:list-widgets
        {--category= : Filter by category}
        {--custom-only : Show only custom (non-built-in) widgets}';

    protected $description = 'List all registered Layup widgets';

    public function handle(): int
    {
        $registry = app(WidgetRegistry::class);
        $this->ensureWidgetsRegistered($registry);

        $widgets = $registry->all();

        if ($widgets === []) {
            $this->warn('No widgets registered.');

            return self::SUCCESS;
        }

        $rows = [];

        foreach ($widgets as $type => $class) {
            $source = $this->detectSource($class);

            if ($this->option('custom-only') && $source === 'built-in') {
                continue;
            }

            $category = $class::getCategory();

            if ($this->option('category') && $category !== $this->option('category')) {
                continue;
            }

            $rows[] = [
                $type,
                $class::getLabel(),
                $category,
                $source,
            ];
        }

        if ($rows === []) {
            $this->warn('No widgets match the given filters.');

            return self::SUCCESS;
        }

        usort($rows, fn (array $a, array $b): int => $a[2] <=> $b[2] ?: $a[0] <=> $b[0]);

        $this->table(['Type', 'Label', 'Category', 'Source'], $rows);

        $builtIn = count(array_filter($rows, fn (array $r): bool => $r[3] === 'built-in'));
        $custom = count($rows) - $builtIn;
        $this->info("{$builtIn} built-in, {$custom} custom (" . count($rows) . ' total)');

        return self::SUCCESS;
    }

    protected function detectSource(string $class): string
    {
        return str_starts_with($class, 'Crumbls\\Layup\\View\\') ? 'built-in' : 'custom';
    }

    protected function ensureWidgetsRegistered(WidgetRegistry $registry): void
    {
        foreach (config('layup.widgets', []) as $class) {
            if (class_exists($class) && ! $registry->has($class::getType())) {
                $registry->register($class);
            }
        }
    }
}
