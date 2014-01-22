<?php namespace Stanley\Geocodio\ServiceProviders;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register 'underlyingclass' instance container to our UnderlyingClass object
        $this->app['geocode'] = $this->app->share(function($app)
        {
            return new Stanley\Geocodio\Client;
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Geocode', 'Stanley\Geocodio\Client');
        });
    }
}

