<?php
namespace Spoob\UnotisLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use Spoob\UnotisLaravel\UnotisClient;


class UnotisServiceProvider extends ServiceProvider
{
    /**
     * Publishes configuration file.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/unotis.php' => config_path('unotis.php'),
            ], 'unotis-config');
        }
    }

    /**
     * Make config publishment optional by merge the config from the package.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/unotis.php',
            'unotis',
        );

        $this->app->bind('unotis-laravel', function()
        {
            return new UnotisClient(config('unotis.token'));
        });
    }

}
