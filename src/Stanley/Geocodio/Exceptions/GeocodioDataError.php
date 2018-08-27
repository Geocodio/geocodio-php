<?php
namespace Stanley\Geocodio\Exception;

class GeocodioDataError extends Exception
{

    public function __construct($reason)
    {
        $message  = $reason->getMessage();
        $code     = $reason->getStatusCode();
        $previous = null;
        parent::__construct($message, $code, $previous);
    }
}
