<?php

namespace Jefre\SpatiePermissionGenerate\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Jefre\SpatiePermissionGenerate\SpatiePermissionGenerate;

/**
 * Service provider
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . '/../config/spatie-permission-generate.php' => config_path('spatie-permission-generate.php'),
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/spatie-permission-generate.php',
            'spatie-permission-generate'
        );

        $this->app->bind(SpatiePermissionGenerate::class, function ($app) {
            $spg =  new \Jefre\SpatiePermissionGenerate\SpatiePermissionGenerate();



            return $spg;
        });
    }
}
