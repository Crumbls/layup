<?php

declare(strict_types=1);

namespace Crumbls\Layup;

use Crumbls\Layup\Console\Commands\AuditCommand;
use Crumbls\Layup\Console\Commands\DebugWidgetCommand;
use Crumbls\Layup\Console\Commands\DoctorCommand;
use Crumbls\Layup\Console\Commands\ExportCommand;
use Crumbls\Layup\Console\Commands\GenerateSafelist;
use Crumbls\Layup\Console\Commands\ImportCommand;
use Crumbls\Layup\Console\Commands\InstallCommand;
use Crumbls\Layup\Console\Commands\ListWidgetsCommand;
use Crumbls\Layup\Console\Commands\MakeControllerCommand;
use Crumbls\Layup\Console\Commands\MakeWidgetCommand;
use Crumbls\Layup\Console\Commands\SearchCommand;
use Crumbls\Layup\Support\LayupTheme;
use Crumbls\Layup\Support\WidgetRegistry;
use Crumbls\Layup\View\Components\LayupWidgetComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LayupServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/layup.php', 'layup');

        $this->app->singleton(WidgetRegistry::class, fn (): WidgetRegistry => new WidgetRegistry);
        $this->app->singleton(LayupTheme::class, fn (): LayupTheme => new LayupTheme);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'layup');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'layup');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateSafelist::class,
                InstallCommand::class,
                MakeWidgetCommand::class,
                MakeControllerCommand::class,
                AuditCommand::class,
                ExportCommand::class,
                ImportCommand::class,
                ListWidgetsCommand::class,
                SearchCommand::class,
                DoctorCommand::class,
                DebugWidgetCommand::class,
            ]);
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

        $this->publishes([
            __DIR__ . '/../stubs/layup-widget.php.stub' => base_path('stubs/layup-widget.php.stub'),
            __DIR__ . '/../stubs/layup-widget-view.blade.php.stub' => base_path('stubs/layup-widget-view.blade.php.stub'),
            __DIR__ . '/../stubs/layup-widget-test.php.stub' => base_path('stubs/layup-widget-test.php.stub'),
        ], 'layup-stubs');

        Blade::component('layup-widget', LayupWidgetComponent::class);

        Blade::directive('layupScripts', fn (): string => "<?php \Crumbls\Layup\Support\ThemeResolver::ensureBooted(); echo '<style>' . app(\Crumbls\Layup\Support\LayupTheme::class)->toCss() . '</style>'; ?>"
            . "<?php if(config('layup.frontend.include_scripts', true)): ?>"
            . '<script>' . file_get_contents(__DIR__ . '/../resources/js/layup.js') . '</script>'
            . '<?php endif; ?>');

        Blade::directive('layup', fn (string $expression): string => "<?php echo (new \Crumbls\Layup\Support\LayupContent({$expression}))->toHtml(); ?>");

        $this->validateConfig();
    }

    protected function validateConfig(): void
    {
        $modelClass = config('layup.pages.model');

        if ($modelClass && ! class_exists($modelClass)) {
            logger()->warning("Layup: pages.model class '{$modelClass}' does not exist.");
        } elseif ($modelClass && ! is_subclass_of($modelClass, Model::class)) {
            logger()->warning("Layup: pages.model '{$modelClass}' does not extend Illuminate\\Database\\Eloquent\\Model.");
        }

        $disk = config('layup.uploads.disk');

        if ($disk && ! config("filesystems.disks.{$disk}")) {
            logger()->warning("Layup: uploads.disk '{$disk}' is not configured in filesystems.php.");
        }

        $table = config('layup.pages.table');

        if (! is_string($table) || $table === '') {
            logger()->warning('Layup: pages.table is empty or not a string.');
        }

        if (config('layup.frontend.enabled', true)) {
            $layout = config('layup.frontend.layout');

            if (! is_string($layout) || $layout === '') {
                logger()->warning('Layup: frontend.layout is empty but frontend is enabled.');
            }
        }
    }
}
