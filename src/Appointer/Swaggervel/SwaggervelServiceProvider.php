<?php

namespace Appointer\Swaggervel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class SwaggervelServiceProvider extends ServiceProvider
{
    /**
     * Full filename of the used config file
     */
    const CONFIG_FILE = 'swaggervel.php';

    /**
     * Full path to the used config file
     */
    const CONFIG_FILE_PATH = __DIR__ . '/../../config/' . self::CONFIG_FILE;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $swaggervelResources = 'vendor/swaggervel';
        $swaggervelViews = __DIR__ . '/../../views/';

        // this publishes the default configuration to the user's application config directory
        $this->publishes([
            self::CONFIG_FILE_PATH => config_path(self::CONFIG_FILE),
        ], 'config');

        // this publishes the assets required to run swaggervel-ui
        $this->publishes([
            base_path('vendor/swagger-api/swagger-ui/dist') => public_path($swaggervelResources),
        ], 'public');

        $this->loadViewsFrom($swaggervelViews, 'swaggervel');

        // this gives the user the opportunity to override the swaggervel index blade file
        $this->publishes([
            $swaggervelViews => base_path('resources/views/' . $swaggervelResources),
        ], 'views');

        if (!$this->app->routesAreCached()) {
            Route::group(['namespace' => 'Appointer\Swaggervel\Http\Controllers'], function () {
                require __DIR__ . '/Http/routes.php';
            });
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_FILE_PATH, 'swaggervel');
    }
}
