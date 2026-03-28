<?php

declare(strict_types=1);

namespace Crumbls\Layup\Console\Commands;

use Filament\Facades\Filament;
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

        // Pre-flight: ensure Filament is installed
        if (! $this->checkFilament()) {
            return self::FAILURE;
        }

        // Publish config
        $this->call('vendor:publish', [
            '--tag' => 'layup-config',
        ]);
        $this->info(__('layup::commands.config_published'));

        // Run migrations
        $this->call('migrate');
        $this->info(__('layup::commands.migrations_completed'));

        // Ensure storage symlink exists
        $this->ensureStorageLink();

        // Ensure frontend layout component exists
        $this->ensureLayoutExists();

        // Publish Filament assets (includes our CSS)
        $this->call('filament:assets');
        $this->info(__('layup::commands.assets_published'));

        // Generate safelist
        $this->callSilent('layup:safelist');
        $this->info(__('layup::commands.safelist_generated'));

        // Check plugin registration
        $this->checkPluginRegistered();

        $this->newLine();
        $this->components->info(__('layup::commands.installed'));
        $this->newLine();

        $this->printNextSteps();

        // Run doctor for a quick health check
        $this->newLine();
        $this->call('layup:doctor');

        return self::SUCCESS;
    }

    protected function checkFilament(): bool
    {
        if (! class_exists(\Filament\FilamentServiceProvider::class)) {
            $this->components->error(__('layup::commands.filament_missing'));
            $this->newLine();
            $this->line('  composer require filament/filament');
            $this->line('  php artisan filament:install --panels');
            $this->newLine();

            return false;
        }

        return true;
    }

    protected function ensureStorageLink(): void
    {
        $link = public_path('storage');

        if (file_exists($link)) {
            $this->info(__('layup::commands.storage_link_exists'));

            return;
        }

        $this->call('storage:link');
        $this->info(__('layup::commands.storage_link_created'));
    }

    protected function ensureLayoutExists(): void
    {
        $layout = config('layup.frontend.layout', 'app');
        $path = resource_path("views/components/{$layout}.blade.php");

        if (File::exists($path)) {
            $this->info(__('layup::commands.layout_exists', ['layout' => $layout]));

            $contents = File::get($path);

            if (! str_contains($contents, '@layupScripts')) {
                $this->components->warn(__('layup::commands.layout_missing_scripts'));
            }

            if (! str_contains($contents, 'app.js') && ! str_contains($contents, 'alpine')) {
                $this->components->warn(__('layup::commands.layout_missing_alpine'));
            }

            return;
        }

        $stub = __DIR__ . '/../../../stubs/app-layout.blade.php.stub';

        File::ensureDirectoryExists(dirname($path));
        File::copy($stub, $path);

        $this->info(__('layup::commands.layout_created', ['layout' => $layout]));
    }

    protected function checkPluginRegistered(): void
    {
        try {
            $found = false;

            foreach (Filament::getPanels() as $panel) {
                if ($panel->hasPlugin('layup')) {
                    $found = true;

                    break;
                }
            }

            if (! $found) {
                $this->newLine();
                $this->components->warn(__('layup::commands.plugin_not_registered'));
            }
        } catch (\Throwable) {
            // Filament not fully booted -- skip check
        }
    }

    protected function printNextSteps(): void
    {
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
        $this->line('     # or: bun run build / pnpm run build / yarn build');
        $this->newLine();
        $this->line('  4. Pages default to "draft" -- publish them to make');
        $this->line('     them visible on the frontend.');
        $this->newLine();
    }
}
