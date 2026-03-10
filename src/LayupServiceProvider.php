<?php

declare(strict_types=1);

namespace Crumbls\Layup;

use Crumbls\Layup\Console\Commands\AuditCommand;
use Crumbls\Layup\Console\Commands\ExportCommand;
use Crumbls\Layup\Console\Commands\GenerateSafelist;
use Crumbls\Layup\Console\Commands\ImportCommand;
use Crumbls\Layup\Console\Commands\InstallCommand;
use Crumbls\Layup\Console\Commands\MakeControllerCommand;
use Crumbls\Layup\Console\Commands\MakeWidgetCommand;
use Crumbls\Layup\Support\WidgetRegistry;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LayupServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/layup.php', 'layup');

        $this->app->singleton(WidgetRegistry::class, fn (): \Crumbls\Layup\Support\WidgetRegistry => new WidgetRegistry);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'layup');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'layup');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([GenerateSafelist::class, InstallCommand::class, MakeWidgetCommand::class, MakeControllerCommand::class, AuditCommand::class, ExportCommand::class, ImportCommand::class]);
        }

        if (config('layup.frontend.enabled', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }

        FilamentAsset::register([
            Css::make('layup', __DIR__ . '/../resources/css/layup.css'),
        ], 'crumbls/layup');

        $this->publishes([
            __DIR__ . '/../config/layup.php' => config_path('layup.php'),
        ], 'layup-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/layup'),
        ], 'layup-views');

        $this->publishes([
            __DIR__ . '/../routes/web.php' => base_path('routes/layup.php'),
        ], 'layup-routes');

        $this->publishes([
            __DIR__ . '/../resources/js/layup.js' => resource_path('js/vendor/layup.js'),
        ], 'layup-scripts');

        $this->publishes([
            __DIR__ . '/../resources/templates' => resource_path('layup/templates'),
        ], 'layup-templates');

        $this->publishes([
            __DIR__ . '/../resources/lang' => $this->app->langPath('vendor/layup'),
        ], 'layup-translations');

        Blade::directive('layupScripts', fn (): string => "<?php if(config('layup.frontend.include_scripts', true)): ?>"
            . '<script>' . file_get_contents(__DIR__ . '/../resources/js/layup.js') . '</script>'
            . '<?php endif; ?>');

        Blade::directive('layup', fn (string $expression): string => "<?php echo (new \Crumbls\Layup\Support\LayupContent({$expression}))->toHtml(); ?>");
    }
}
