<?php namespace Stanley\Geocodio;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Stanley\Geocodio\Data;
use Stanley\Geocodio\Exception\GeocodioAuthError;
use Stanley\Geocodio\Exception\GeocodioDataError;
use Stanley\Geocodio\Exception\GeocodioException;
use Stanley\Geocodio\Exception\GeocodioServerError;

class Client
{
    const BASE_URL = 'https://%s/v1.3/';

    /**
     * API Key
     * @var string
     */
    protected $apiKey;

    /**
     * Geocodio API hostname
     * @var string
     */
    protected $hostname;

    /**
     * Guzzle Client object
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * Class constructor
     *
     * @param string $apiKey API Key
     */
    public function __construct($apiKey = null, $hostname = 'api.geocod.io')
    {
        $this->apiKey   = $apiKey;
        $this->hostname = $hostname;
        $this->client   = $this->newGuzzleClient();
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

        if ($key) {
            $this->apiKey = $key;
        }

        return (is_string($data)) ? $this->get($data, $fields) : $this->post($data, $fields);
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

        if ($key) {
            $this->apiKey = $key;
        }

        $request = $this->getRequest($data, $fields, $verb);
        return $this->newDataObject($request->getBody());
    }

    /**
     * Post Method
     *
     * @param  array $data Data to be encoded
     * @return Stanley\Geocodio\Data
     */
    public function post($data, $fields = [], $key = null, $verb = 'geocode')
    {

        if ($key) {
            $this->apiKey = $key;
        }

        $request = $this->bulkPost($data, $fields, $verb);
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

        if ($key) {
            $this->apiKey = $key;
        }

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
     * @return GuzzleHttp\Psr7\Response
     */
    protected function getRequest($data, $fields, $verb)
    {
        try {
            $params = [
                'q'       => $data,
                'api_key' => $this->apiKey,
                'fields'  => implode(',', $fields),
            ];

            $response = $this->client->get($verb, [
                'query' => $params,
            ]);
            return $response;
        } catch (RequestException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $reason     = $e->getResponse()->getReasonPhrase();

            if ($statusCode == 422) {
                throw new GeocodioDataError($e->getResponse());
            } elseif ($statusCode == 403) {
                throw new GeocodioAuthError($e->getResponse());
            } else {
                throw new GeocodioException("There was a problem with your request - {$reason}");
            }

        } catch (ServerException $e) {
            throw new GeocodioServerError($e->getResponse());
        }

    }

    /**
     * Call Guzzle Post request method
     *
     * @param  array $data Address data
     * @param  string $verb Method to be called - should only be geocode for now
     * @return GuzzleHttp\Psr7\Response
     */
    protected function bulkPost($data, $fields, $verb = 'geocode')
    {
        try {
            $params = [
                'api_key' => $this->apiKey,
                'fields'  => implode(',', $fields),
            ];

            $response = $this->client->post($verb, [
                'query' => $params,
                'json'  => $data,
            ]);

            return $response;
        } catch (RequestException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $reason     = $e->getResponse()->getReasonPhrase();

            if ($statusCode == 422) {
                throw new GeocodioDataError($e->getResponse());
            } elseif ($statusCode == 403) {
                throw new GeocodioAuthError($e->getResponse());
            } else {
                throw new GeocodioException("There was a problem with your request - " . $reason);
            }

        } catch (ServerException $e) {
            throw new GeocodioServerError($e->getResponse());
        }

    }

    /**
     * Create new Guzzle Client
     *
     * @return GuzzleHttp\Client
     */
    protected function newGuzzleClient()
    {
        $baseUrl = sprintf(self::BASE_URL, $this->hostname);

        return new GuzzleClient([
            'base_uri' => $baseUrl,
        ]);
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
