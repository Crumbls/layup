<?php

declare(strict_types=1);

namespace Crumbls\Layup\Console\Commands;

use Crumbls\Layup\Models\Page;
use Crumbls\Layup\Support\ContentWalker;
use Crumbls\Layup\Support\WidgetRegistry;
use Illuminate\Console\Command;

class SearchCommand extends Command
{
    protected $signature = 'layup:search
        {type? : Widget type to search for}
        {--unused : Find registered widget types not used in any page}';

    protected $description = 'Find pages containing a widget type, or find unused widget types';

    public function handle(): int
    {
        if ($this->option('unused')) {
            return $this->handleUnused();
        }

        $type = $this->argument('type');

        if (! $type) {
            $this->error('Please provide a widget type or use --unused.');

            return self::FAILURE;
        }

        return $this->handleSearch($type);
    }

    protected function handleSearch(string $type): int
    {
        $modelClass = config('layup.pages.model', Page::class);
        $pages = $modelClass::all();

        $matches = [];

        foreach ($pages as $page) {
            $content = $page->content ?? [];
            $types = ContentWalker::collectWidgetTypes($content);

            if (isset($types[$type])) {
                $matches[] = [
                    'title' => $page->title,
                    'count' => $types[$type],
                    'status' => $page->status,
                ];
            }
        }

        if ($matches === []) {
            $this->info("No pages contain the '{$type}' widget.");

            return self::SUCCESS;
        }

        usort($matches, fn (array $a, array $b): int => $b['count'] <=> $a['count']);

        $total = array_sum(array_column($matches, 'count'));
        $this->info("Found '{$type}' widget in " . count($matches) . " page(s) ({$total} instance(s)):");

        foreach ($matches as $match) {
            $status = $match['status'] === 'draft' ? ' [draft]' : '';
            $this->line("  \"{$match['title']}\"{$status} ({$match['count']} instance(s))");
        }

        return self::SUCCESS;
    }

    protected function handleUnused(): int
    {
        $registry = app(WidgetRegistry::class);
        $this->ensureWidgetsRegistered($registry);

        $registeredTypes = array_keys($registry->all());

        $modelClass = config('layup.pages.model', Page::class);
        $pages = $modelClass::all();

        $usedTypes = [];

        foreach ($pages as $page) {
            $content = $page->content ?? [];
            $types = ContentWalker::collectWidgetTypes($content);

            foreach (array_keys($types) as $type) {
                $usedTypes[$type] = true;
            }
        }

        $unused = array_filter(
            $registeredTypes,
            fn (string $type): bool => ! isset($usedTypes[$type])
        );

        if ($unused === []) {
            $this->info('All registered widget types are used in at least one page.');

            return self::SUCCESS;
        }

        sort($unused);
        $this->info('Widgets not used in any page (' . count($unused) . '):');

        foreach ($unused as $type) {
            $this->line("  {$type}");
        }

        return self::SUCCESS;
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
