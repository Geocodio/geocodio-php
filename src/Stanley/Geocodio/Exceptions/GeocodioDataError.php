<?php
namespace Stanley\Geocodio\Exception;

class GeocodioDataError extends \Exception
{

    public function __construct($response)
    {
        $message  = $response->getBody()->getContents();
        $code     = $response->getStatusCode();
        $previous = null;
        parent::__construct($message, $code, $previous);
    }
}
