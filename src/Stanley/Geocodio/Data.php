<?php namespace Stanley\Geocodio;

class Data
{
    /**
     * Response Body
     * @var object
     */
    public $data = [];

    /**
     * Class Constructor
     *
     * @param obj $data Response data
     */
    public function __construct($data)
    {
        $this->data = json_decode($data);
    }
}