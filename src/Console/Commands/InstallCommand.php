<?php

declare(strict_types=1);

namespace Crumbls\Layup\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'layup:install';

    protected $description = 'Install Layup page builder — publish config, run migrations, and print next steps';

    public function handle(): int
    {
        $this->info(__('layup::commands.installing'));
        $this->newLine();

        // Publish config
        $this->call('vendor:publish', [
            '--tag' => 'layup-config',
        ]);
        $this->info(__('layup::commands.config_published'));

        // Run migrations
        $this->call('migrate');
        $this->info(__('layup::commands.migrations_completed'));

        // Ensure frontend layout component exists
        $this->ensureLayoutExists();

        // Publish Filament assets (includes our CSS)
        $this->call('filament:assets');
        $this->info(__('layup::commands.assets_published'));

        // Generate safelist
        $this->callSilent('layup:safelist');
        $this->info(__('layup::commands.safelist_generated'));

        $this->newLine();
        $this->components->info(__('layup::commands.installed'));
        $this->newLine();

        $this->comment(__('layup::commands.next_steps'));
        $this->newLine();
        $this->line('  1. Register the plugin in your Filament panel:');
        $this->newLine();
        $this->line('     ->plugins([');
        $this->line('         \Crumbls\Layup\LayupPlugin::make(),');
        $this->line('     ])');
        $this->newLine();
        $this->line('  2. Add the safelist to your Tailwind config:');
        $this->newLine();
        $this->line('     // tailwind.config.js (v3)');
        $this->line('     content: [\'./storage/layup-safelist.txt\']');
        $this->newLine();
        $this->line('     // app.css (v4)');
        $this->line('     @source "../../storage/layup-safelist.txt";');
        $this->newLine();
        $this->line('  3. Rebuild your frontend assets:');
        $this->newLine();
        $this->line('     npm run build');
        $this->newLine();

        return self::SUCCESS;
    }

    protected function ensureLayoutExists(): void
    {
        $layout = config('layup.frontend.layout', 'app');
        $path = resource_path("views/components/{$layout}.blade.php");

        if (File::exists($path)) {
            $this->info(__('layup::commands.layout_exists', ['layout' => $layout]));

            return;
        }

        $stub = __DIR__ . '/../../../stubs/app-layout.blade.php.stub';

        File::ensureDirectoryExists(dirname($path));
        File::copy($stub, $path);

        $this->info(__('layup::commands.layout_created', ['layout' => $layout]));
    }
}
