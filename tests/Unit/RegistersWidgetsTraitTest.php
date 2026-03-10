<?php

declare(strict_types=1);

use Crumbls\Layup\Support\WidgetRegistry;

it('new trait location is usable', function (): void {
    $class = new class
    {
        use \Crumbls\Layup\Support\Concerns\RegistersWidgets;

        public function run(): void
        {
            $this->ensureWidgetsRegistered();
        }
    };

    $class->run();
    $registry = app(WidgetRegistry::class);
    expect(count($registry->all()))->toBeGreaterThan(0);
});

it('deprecated trait location still works', function (): void {
    // Reset registry to verify the deprecated trait still registers widgets
    $this->app->singleton(WidgetRegistry::class, fn () => new WidgetRegistry);

    $class = new class
    {
        use \Crumbls\Layup\Http\Controllers\Concerns\RegistersWidgets;

        public function run(): void
        {
            $this->ensureWidgetsRegistered();
        }
    };

    $class->run();
    $registry = app(WidgetRegistry::class);
    expect(count($registry->all()))->toBeGreaterThan(0);
});
