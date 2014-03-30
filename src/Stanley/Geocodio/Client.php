<?php namespace Stanley\Geocodio;

use Stanley\Geocodio\Data;
use Guzzle\Http\Client as GC;
use Guzzle\Http\Message\Response;

class Client
{
    const BASE_URL = 'http://api.geocod.io/v1/';

    /**
     * API Key
     * @var string
     */
    protected $apiKey;

    /**
     * Guzzle Client object
     * @var Guzzle\Http\Client
     */
    protected $client;

    /**
     * Class constructor
     *
     * @param string $apiKey API Key
     */
    public function __construct($apiKey = null)
    {
        $this->apiKey = $apiKey;
        $this->client = $this->newGuzzleClient();
    }

    /**
     * Setter for API Key
     *
     * @param string $apiKey API Key
     * @return void
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Geocode facade
     *
     * @param  mixed $data incoming data
     * @param  string $key  API Key
     * @return mixed
     */
    public function geocode($data, $fields = [], $key = null)
    {
        if ($key) $this->apiKey = $key;
        return (is_string($data)) ? $this->get($data, $fields) : $this->post($data);
    }
    /**
     * Get Method
     *
     * @param  string $data Data to be encoded
     * @param  string $verb URL segment to call - either 'geocode' or 'parse'
     * @return Stanley\Geocodio\Data
     */
    public function get($data, $fields = [], $key = null, $verb = 'geocode')
    {
        if ($key) $this->apiKey = $key;
        $request = $this->getRequest($data, $fields, $verb);
        return $this->newDataObject($request->getBody());
    }

    /**
     * Post Method
     *
     * @param  array $data Data to be encoded
     * @return Stanley\Geocodio\Data
     */
    public function post($data, $fields = [], $key = null)
    {
        if ($key) $this->apiKey = $key;
        $request = $this->bulkPost($data, $fields);
        return $this->newDataObject($request->getBody());
    }

    /**
     * Parse method
     *
     * @param  string $data Data to be encoded
     * @return Stanley\Geocodio\Data
     */
    public function parse($data, $key = null)
    {
        if ($key) $this->apiKey = $key;
        return $this->get($data, [], null, 'parse');
    }

    /**
     * Reverse Method
     *
     * @param  mixed  $data Information to encode
     * @param  string $key  API Key
     * @return Stanley\Geocodio\Data
     */
    public function reverse($data, $fields = [], $key = null)
    {
        return (is_string($data)) ? $this->get($data, $fields, $key, 'reverse') : $this->post($data, $fields, $key, 'reverse');
    }

    /**
     * Call Guzzle with Get Request
     *
     * @param  string $data Address Data
     * @param  string $verb The method being called - geocode, parse, or reverse
     * @return Guzzle\Http\Message\Response
     */
    protected function getRequest($data, $fields, $verb)
    {
        $params = [
            'q' => urlencode($data),
            'api_key' => $this->apiKey,
            'fields' => implode(',', $fields)
        ];
        $request = $this->client->get(self::BASE_URL . $verb, [], [
            'query' => $params
        ]);
        $response = $this->client->send($request);
        return $this->checkResponse($response);
    }

    /**
     * Call Guzzle Post request method
     *
     * @param  array $data Address data
     * @param  string $verb Method to be called - should only be geocode for now
     * @return Guzzle\Http\Message\Response
     */
    protected function bulkPost($data, $fields, $verb = 'geocode')
    {
        $url = self::BASE_URL . $verb . "?fields=" . implode(',', $fields) . "&api_key=" . $this->apiKey;
        $headers = [ 'Content-Type' => 'application/json' ];
        $payload = json_encode($data);

        $request = $this->client->post($url, $headers, $payload, []);
        $response = $this->client->send($request);
        return $this->checkResponse($response);
    }

    /**
     * Check response code and throw appropriate exception
     *
     * @param  Guzzle\Http\Message\Respone $response Guzzle Response
     * @return mixed
     */
    protected function checkResponse(Response $response)
    {
        $status = $response->getStatusCode();
        $reason = $response->getReasonPhrase();
        switch ($status) {
            case '403':
                throw new Stanley\Geocodio\GeocodioAuthError($reason);
                break;

            case '422':
                throw new Stanley\Geocodio\GeocodioDataError($reason);
                break;

            case '500':
                throw new Stanley\Geocodio\GeocodioServerError($reason);
                break;

            case '200':
                return $response;
                break;

            default:
                throw new Stanley\Geocodio\GeocodioException("There was a problem with your request - $reason");
                break;
        }
    }

    /**
     * Create new Guzzle Client
     *
     * @return Guzzle\Http\Client
     */
    protected function newGuzzleClient()
    {
        return new GC();
    }

    /**
     * Create new Data object
     *
     * @param  mixed $response Response body
     * @return Stanley\Geocodio\Data
     */
    protected function newDataObject($response)
    {
        return new Data($response);
    }
}