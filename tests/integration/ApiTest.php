<?php

class ApiTest extends BaseTest
{
    protected $apiKey;

    /**
     * Class constructor
     */
    public function __construct()
    {
        // I put my real key in this file within an array.  These tests won't run without it.
        $key = require __DIR__ .'/../../apiKey.php';
        $this->apiKey = $key['apiKey'];
    }

    /**
     * @test
     */
    public function testSingleGoodAddress()
    {
        $goodAddress = '42370 Bob Hope Drive, Rancho Mirage, CA 22334';
        $client = new Stanley\Geocodio\Client($this->apiKey);
        $result = $client->geocode($goodAddress);
        $this->assertInstanceOf('Stanley\Geocodio\Data', $result);
    }

    /**
     * @test
     */
    public function testParseAddress()
    {
        $badAddress = '123 Anywhere St Anytown, CA 12345';
        $client = new Stanley\Geocodio\Client($this->apiKey);
        $result = $client->parse($badAddress);
        $this->assertInstanceOf('Stanley\Geocodio\Data', $result);
    }

    /**
     * @test
     */
    public function testPostMultipleAddresses()
    {
        $multipleAddresses = [
            '42370 Bob Hope Drive, Rancho Mirage CA',
            '1290 Northbrook Court Mall, Northbrook IL'
        ];
        $client = new Stanley\Geocodio\Client($this->apiKey);
        $result = $client->post($multipleAddresses);
        $this->assertInstanceOf('Stanley\Geocodio\Data', $result);
    }
}