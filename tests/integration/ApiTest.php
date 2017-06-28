<?php

class ApiTest extends BaseTest
{
    protected $apiKey;

    public function __construct()
    {
        // I put my real key in this file within an array.  These tests won't run without it.
        $key = require __DIR__ .'/../../apiKey.php';
        $this->apiKey = $key['apiKey'];
    }

    public function testSingleGoodAddress()
    {
        $goodAddress = '42370 Bob Hope Drive, Rancho Mirage, CA 22334';
        $client = new Stanley\Geocodio\Client($this->apiKey);
        $result = $client->geocode($goodAddress);
        $this->assertInstanceOf('Stanley\Geocodio\Data', $result);
    }

    public function testGeocodeMultipleAddresses()
    {
        $multipleAddresses = [
            '42370 Bob Hope Drive, Rancho Mirage CA',
            '1290 Northbrook Court Mall, Northbrook IL'
        ];
        $client = new Stanley\Geocodio\Client($this->apiKey);
        $result = $client->geocode($multipleAddresses);
        $this->assertInstanceOf('Stanley\Geocodio\Data', $result);
        $this->assertEquals(2, count($result->response->results));
    }

    public function testReverseGeocodeMultipleLocations()
    {
        $multipleLocations = [
            '33.73,-116.40',
            '42.15,-87.81'
        ];
        $client = new Stanley\Geocodio\Client($this->apiKey);
        $result = $client->reverse($multipleLocations);
        $this->assertInstanceOf('Stanley\Geocodio\Data', $result);
        $this->assertEquals(2, count($result->response->results));
        $this->assertEquals('nearest_street', $result->response->results[0]->response->results[0]->accuracy_type);
        $this->assertEquals('nearest_street', $result->response->results[1]->response->results[0]->accuracy_type);
    }

    public function testParseAddress()
    {
        $badAddress = '123 Anywhere St Anytown, CA 12345';
        $client = new Stanley\Geocodio\Client($this->apiKey);
        $result = $client->parse($badAddress);
        $this->assertInstanceOf('Stanley\Geocodio\Data', $result);
    }

    public function testFields()
    {
        $goodAddress = '42370 Bob Hope Drive, Rancho Mirage, CA 22334';
        $client = new Stanley\Geocodio\Client($this->apiKey);
        $result = $client->geocode($goodAddress, ['cd', 'stateleg']);
        $this->assertInstanceOf('Stanley\Geocodio\Data', $result);
    }

}