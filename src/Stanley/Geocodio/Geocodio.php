<?php namespace Stanley\Geocodio;

use Illuminate\Support\Facades\Facade;

class Geocodio extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'geocodio'; }

}