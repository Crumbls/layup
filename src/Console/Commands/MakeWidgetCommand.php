<?php

declare(strict_types=1);

namespace Crumbls\Layup\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeWidgetCommand extends Command
{
    protected $signature = 'layup:make-widget
        {name : Widget class name (e.g. BannerWidget)}
        {--with-test : Generate a Pest test file for the widget}';

    protected $description = 'Scaffold a new Layup widget (PHP class + Blade view)';

    public function handle(): int
    {
        $name = $this->argument('name');
        $className = Str::studly($name);
        if (! str_ends_with($className, 'Widget')) {
            $className .= 'Widget';
        }

        $type = Str::kebab(Str::replaceLast('Widget', '', $className));
        $namespace = 'App\\Layup\\Widgets';
        $phpPath = app_path("Layup/Widgets/{$className}.php");
        $bladePath = resource_path("views/components/layup/{$type}.blade.php");

        if (file_exists($phpPath)) {
            $this->error(__('layup::commands.widget_exists', ['path' => $phpPath]));

            return self::FAILURE;
        }

        // Create PHP class
        $phpDir = dirname($phpPath);
        if (! is_dir($phpDir)) {
            mkdir($phpDir, 0755, true);
        }

        $stub = $this->resolveStub('layup-widget.php.stub');
        $stub = str_replace(
            ['{{ namespace }}', '{{ className }}', '{{ type }}'],
            [$namespace, $className, $type],
            $stub,
        );
        file_put_contents($phpPath, $stub);
        $this->info(__('layup::commands.widget_created', ['path' => $phpPath]));

        // Create Blade view
        $bladeDir = dirname($bladePath);
        if (! is_dir($bladeDir)) {
            mkdir($bladeDir, 0755, true);
        }

        $bladeStub = $this->resolveStub('layup-widget-view.blade.php.stub');
        file_put_contents($bladePath, $bladeStub);
        $this->info(__('layup::commands.blade_created', ['path' => $bladePath]));

        // Create test file
        if ($this->option('with-test')) {
            $testPath = base_path("tests/Unit/Layup/{$className}Test.php");
            $testDir = dirname($testPath);

            if (! is_dir($testDir)) {
                mkdir($testDir, 0755, true);
            }

            $testStub = $this->resolveStub('layup-widget-test.php.stub');
            $testStub = str_replace(
                ['{{ namespace }}', '{{ className }}'],
                [$namespace, $className],
                $testStub,
            );
            file_put_contents($testPath, $testStub);
            $this->info("Test created: {$testPath}");
        }

        $this->newLine();
        $this->comment(__('layup::commands.next_steps'));
        $this->line("  1. Edit {$phpPath} to add your form fields");
        $this->line("  2. Edit {$bladePath} to customize the frontend HTML");
        $this->line('  3. The widget will be auto-discovered from App\\Layup\\Widgets');
        $this->line('     Or add it to config/layup.php widgets array:');
        $this->line("     \\{$namespace}\\{$className}::class,");

        return self::SUCCESS;
    }

    /**
     * Resolve a stub file, checking for published stubs first.
     */
    protected function resolveStub(string $stubName): string
    {
        $publishedPath = base_path("stubs/{$stubName}");

        if (file_exists($publishedPath)) {
            return file_get_contents($publishedPath);
        }

        return file_get_contents(__DIR__ . '/../../../stubs/' . $stubName);
    }

    protected function humanize(string $kebab): string
    {
        return Str::title(str_replace('-', ' ', $kebab));
    }
}
