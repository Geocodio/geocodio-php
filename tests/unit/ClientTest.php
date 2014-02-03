<?php

class ClientTest extends BaseTest
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $apiKey = 'asdf';
        $client = $this->getMockBuilder('Stanley\Geocodio\Client')
            ->setConstructorArgs(array($apiKey))
            ->getMock();

        $testKey = $this->getAttribute($client, 'apiKey');
        $testClient = $this->getAttribute($client, 'client');

        $this->assertEquals($testKey, $apiKey);
        $this->assertInstanceOf('Guzzle\Http\Client', $testClient);
    }

    /**
     * @test
     */
    public function testGetMethod()
    {
        $data = ['test', 'data'];
        $dataObject = new Stanley\Geocodio\Data(json_encode($data));

        $returnData = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->setMethods(array('getBody'))
            ->getMock();
        $returnData->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue(json_encode($data)));

        $client = $this->getMockBuilder('Stanley\Geocodio\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('getRequest', 'newDataObject'))
            ->getMock();
        $client->expects($this->once())
            ->method('getRequest')
            ->with($this->equalTo($data),
                   $this->equalTo('geocode'))
            ->will($this->returnValue($returnData));
        $client->expects($this->once())
            ->method('newDataObject')
            ->with($this->equalTo(json_encode($data)))
            ->will($this->returnValue($dataObject));

        $response = $client->get($data);
        $this->assertInstanceOf('Stanley\Geocodio\Data', $response);
    }

    /**
     * @test
     */
    public function testPostMethod()
    {
        $data = new stdClass();
        $data->test = 'test';
        $data->info = 'info';

        $dataObject = new Stanley\Geocodio\Data(json_encode($data));
        $returnData = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->setMethods(array('getBody'))
            ->getMock();
        $returnData->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue(json_encode($data)));

        $client = $this->getMockBuilder('Stanley\Geocodio\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('bulkPost', 'newDataObject'))
            ->getMock();
        $client->expects($this->once())
            ->method('bulkPost')
            ->with($this->equalTo($data))
            ->will($this->returnValue($returnData));
        $client->expects($this->once())
            ->method('newDataObject')
            ->with($this->equalTo(json_encode($data)))
            ->will($this->returnValue($dataObject));

        $response = $client->post($data);
        $this->assertInstanceOf('Stanley\Geocodio\Data', $response);
    }

    /**
     * @test
     */
    public function testParseMethod()
    {
        $data = ['test', 'data'];

        $dataObject = new Stanley\Geocodio\Data(json_encode($data));
        $client = $this->getMockBuilder('Stanley\Geocodio\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock();
        $client->expects($this->once())
            ->method('get')
            ->with($this->equalTo($data),
                   $this->equalTo(null),
                   $this->equalTo('parse'))
            ->will($this->returnValue($dataObject));

        $response = $client->parse($data);
        $this->assertInstanceOf('Stanley\Geocodio\Data', $response);
    }

    /**
     * @test
     */
    public function testGetRequestMethod()
    {
        $baseUrl = 'http://api.geocod.io/v1/geocode';
        $data = ['test', 'data'];
        $params = [
            'query' => [
                'q' => $data,
                'api_key' => 'asdf',
            ]
        ];
        $returnData = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $guzzle = $this->getMockBuilder('Guzzle\Http\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('get', 'send'))
            ->getMock();
        $guzzle->expects($this->once())
            ->method('get')
            ->with($this->equalTo($baseUrl),
                   $this->equalTo(array()),
                   $this->equalTo($params))
            ->will($this->returnSelf());
        $guzzle->expects($this->once())
            ->method('send')
            ->will($this->returnValue($returnData));

        $mock = $this->getMockBuilder('Stanley\Geocodio\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('checkResponse'))
            ->getMock();
        $mock->expects($this->once())
            ->method('checkResponse')
            ->will($this->returnValue($returnData));

        $this->setAttribute($mock, 'apiKey', 'asdf');
        $this->setAttribute($mock, 'client', $guzzle);

        $response = $this->invokeMethod($mock, 'getRequest', array($data, 'geocode'));
        $this->assertEquals($returnData, $response);
    }

    /**
     * @test
     */
    public function testBulkPostMethod()
    {
        $baseUrl = 'http://api.geocod.io/v1/geocode';
        $url = $baseUrl .'?api_key=asdf';
        $data = ['test', 'data'];
        $headers = [ 'Content-Type' => 'application/json' ];
        $payload = json_encode($data);

        $returnData = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $guzzle = $this->getMockBuilder('Guzzle\Http\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('post', 'send'))
            ->getMock();
        $guzzle->expects($this->once())
            ->method('post')
            ->with($this->equalTo($url),
                   $this->equalTo($headers),
                   $this->equalTo($payload))
            ->will($this->returnSelf());
        $guzzle->expects($this->once())
            ->method('send')
            ->will($this->returnValue($returnData));

        $mock = $this->getMockBuilder('Stanley\Geocodio\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('checkResponse'))
            ->getMock();
        $mock->expects($this->once())
            ->method('checkResponse')
            ->will($this->returnValue($returnData));

        $this->setAttribute($mock, 'apiKey', 'asdf');
        $this->setAttribute($mock, 'client', $guzzle);

        $response = $this->invokeMethod($mock, 'bulkPost', array($data, 'geocode'));
        $this->assertEquals($returnData, $response);
    }

    /**
     * @test
     */
    public function testNewGuzzleClientCreated()
    {
        $mock = $this->getMockBuilder('\Stanley\Geocodio\Client')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $client = $this->invokeMethod($mock, 'newGuzzleClient');

        $this->assertInstanceOf(
            '\Guzzle\Http\Client',
            $client
        );
    }

    /**
     * @test
     */
    public function testNewDataObjectMethod()
    {
        $data = ['test', 'data'];
        $json = json_encode($data);
        $actual = new Stanley\Geocodio\Data($json);

        $mock = $this->getMockBuilder('Stanley\Geocodio\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $test = $this->invokeMethod($mock, 'newDataObject', array($json));
        $this->assertEquals($actual, $test);
    }
}