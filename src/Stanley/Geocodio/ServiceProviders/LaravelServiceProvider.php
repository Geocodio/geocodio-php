<?php namespace Stanley\Geocodio\ServiceProviders;

use Stanley\Geocodio\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class LaravelServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register 'geocodio' instance container to our Geocodio object
        $this->app->bind('geocodio', function () {
            return new Client(env('GEOCODIO_API_KEY'));
        });
    }
}

