<?php

declare(strict_types=1);

it('scaffolds blade widget by default', function (): void {
    config()->set('layup.frontend.driver', 'blade');

    $phpPath = app_path('Layup/Widgets/BladeDefaultWidget.php');
    $bladePath = resource_path('views/components/layup/blade-default.blade.php');

    @unlink($phpPath);
    @unlink($bladePath);

    $this->artisan('layup:make-widget', ['name' => 'BladeDefault'])
        ->assertSuccessful();

    expect(file_exists($phpPath))->toBeTrue();
    expect(file_exists($bladePath))->toBeTrue();

    @unlink($phpPath);
    @unlink($bladePath);
    @rmdir(app_path('Layup/Widgets'));
    @rmdir(app_path('Layup'));
    @rmdir(resource_path('views/components/layup'));
});
