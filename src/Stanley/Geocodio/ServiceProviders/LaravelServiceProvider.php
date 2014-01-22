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
        // Register 'geocodio' instance container to our Geocodio object
        // Get the API key from configs if it is set
        $key = Config::get('geocodio.key') ?: null;
        $this->app['geocode'] = $this->app->share(function($app, $key)
        {
            return new Stanley\Geocodio\Client($key);
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Geocodio', 'Stanley\Geocodio\Client');
        });
    }
}

