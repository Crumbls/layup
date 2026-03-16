<?php

declare(strict_types=1);

namespace Crumbls\Layup\Console\Commands;

use Crumbls\Layup\Models\Page;
use Crumbls\Layup\Support\WidgetRegistry;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;

class DoctorCommand extends Command
{
    protected $signature = 'layup:doctor';

    protected $description = 'Diagnose common Layup setup issues';

    protected int $passes = 0;

    protected int $warnings = 0;

    protected int $failures = 0;

    public function handle(): int
    {
        $this->info('Layup Doctor');
        $this->line(str_repeat('─', 40));
        $this->newLine();

        $this->checkConfig();
        $this->checkMigrations();
        $this->checkWidgets();
        $this->checkWidgetViews();
        $this->checkSafelist();
        $this->checkUploadDisk();
        $this->checkPageStats();

        $this->newLine();
        $this->line(str_repeat('─', 40));
        $this->info("{$this->passes} passed, {$this->warnings} warning(s), {$this->failures} failure(s)");

        return $this->failures > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function checkConfig(): void
    {
        $modelClass = config('layup.pages.model', Page::class);

        if (! class_exists($modelClass)) {
            $this->reportFail("pages.model class '{$modelClass}' does not exist");
        } elseif (! is_subclass_of($modelClass, Model::class)) {
            $this->reportFail("pages.model '{$modelClass}' does not extend Illuminate\\Database\\Eloquent\\Model");
        } else {
            $this->reportPass('pages.model is valid');
        }

        $disk = config('layup.uploads.disk', 'public');

        if (! config("filesystems.disks.{$disk}")) {
            $this->reportFail("uploads.disk '{$disk}' is not configured in filesystems.php");
        } else {
            $this->reportPass("Upload disk '{$disk}' is configured");
        }

        $table = config('layup.pages.table', 'layup_pages');

        if (! is_string($table) || $table === '') {
            $this->reportFail('pages.table is empty or invalid');
        } else {
            $this->reportPass('pages.table is set');
        }

        if (config('layup.frontend.enabled', true)) {
            $layout = config('layup.frontend.layout', '');

            if (! is_string($layout) || $layout === '') {
                $this->reportWarn('frontend.layout is empty (frontend is enabled)');
            } else {
                $this->reportPass("Frontend layout '{$layout}' is set");
            }
        }
    }

    protected function checkMigrations(): void
    {
        try {
            $table = config('layup.pages.table', 'layup_pages');
            $exists = \Illuminate\Support\Facades\Schema::hasTable($table);

            if ($exists) {
                $this->reportPass("Table '{$table}' exists");
            } else {
                $this->reportFail("Table '{$table}' does not exist (run php artisan migrate)");
            }
        } catch (\Throwable $e) {
            $this->reportWarn('Could not check migrations: ' . $e->getMessage());
        }
    }

    protected function checkWidgets(): void
    {
        $registry = app(WidgetRegistry::class);
        $this->ensureWidgetsRegistered($registry);

        $all = $registry->all();
        $builtIn = count(array_filter($all, fn (string $class): bool => str_starts_with($class, 'Crumbls\\Layup\\View\\')));
        $custom = count($all) - $builtIn;

        $this->reportPass(count($all) . " widgets registered ({$builtIn} built-in, {$custom} custom)");

        // Check for type collisions in config
        $configWidgets = config('layup.widgets', []);
        $seen = [];

        foreach ($configWidgets as $class) {
            if (! class_exists($class)) {
                continue;
            }

            $type = $class::getType();

            if (isset($seen[$type])) {
                $this->reportWarn("Widget type '{$type}' registered by both {$seen[$type]} and {$class}");
            }

            $seen[$type] = $class;
        }
    }

    protected function checkWidgetViews(): void
    {
        $registry = app(WidgetRegistry::class);
        $missing = [];

        foreach ($registry->all() as $type => $class) {
            $viewName = 'layup::components.' . $type;

            if (! View::exists($viewName)) {
                // Check for custom view path
                $customView = 'components.layup.' . $type;

                if (! View::exists($customView)) {
                    $missing[] = $type;
                }
            }
        }

        if ($missing === []) {
            $this->reportPass('All registered widgets have Blade views');
        } else {
            foreach ($missing as $type) {
                $this->reportFail("Widget '{$type}' has no Blade view");
            }
        }
    }

    protected function checkSafelist(): void
    {
        if (! config('layup.safelist.enabled')) {
            $this->reportPass('Safelist is disabled (skipping)');

            return;
        }

        $path = config('layup.safelist.path', 'storage/layup-safelist.txt');
        $fullPath = base_path($path);

        if (! file_exists($fullPath)) {
            $this->reportWarn("Safelist file not found at {$path} (run layup:safelist)");

            return;
        }

        $this->reportPass('Safelist file exists');

        $mtime = filemtime($fullPath);
        $daysSinceUpdate = (int) ((time() - $mtime) / 86400);

        if ($daysSinceUpdate > 3) {
            $this->reportWarn("Safelist file is {$daysSinceUpdate} days stale (run layup:safelist)");
        }
    }

    protected function checkUploadDisk(): void
    {
        $disk = config('layup.uploads.disk', 'public');

        if (config("filesystems.disks.{$disk}")) {
            $this->reportPass("Upload disk '{$disk}' is configured");
        } else {
            $this->reportFail("Upload disk '{$disk}' is not configured");
        }
    }

    protected function checkPageStats(): void
    {
        try {
            $modelClass = config('layup.pages.model', Page::class);

            if (! class_exists($modelClass)) {
                return;
            }

            $published = $modelClass::where('status', 'published')->count();
            $drafts = $modelClass::where('status', 'draft')->count();

            $this->info("  Pages: {$published} published, {$drafts} drafts");
        } catch (\Throwable) {
            // Table may not exist yet
        }
    }

    protected function reportPass(string $message): void
    {
        $this->line("  [pass] {$message}");
        $this->passes++;
    }

    protected function reportWarn(string $message): void
    {
        $this->line("  <comment>[warn]</comment> {$message}");
        $this->warnings++;
    }

    protected function reportFail(string $message): void
    {
        $this->line("  <error>[fail]</error> {$message}");
        $this->failures++;
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
