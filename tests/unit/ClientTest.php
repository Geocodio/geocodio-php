<?php

use Stanley\Geocodio\Client;

class ClientTest extends BaseTest
{
    public function testCheckResponseReturnsResponse()
    {
        $client = new Client('');

        $mock = $this->getMock('GuzzleHttp\Psr7\Response');
        $mock->expects($this->any())->method('getStatusCode')->will($this->returnValue('200'));

        $response = $this->invokeMethod($client, 'checkResponse', [$mock]);

        $this->assertEquals($mock, $response);
    }

    public function testCheckResponseThrowsAuthError()
    {
        $client = new Client('');

        $mock = $this->getMock('GuzzleHttp\Psr7\Response');
        $mock->expects($this->any())->method('getStatusCode')->will($this->returnValue('403'));
        $mock->expects($this->any())->method('getReasonPhrase')->will($this->returnValue('Forbidden'));

        $this->setExpectedException('Stanley\Geocodio\Exceptions\GeocodioAuthError', 'Forbidden');

        $this->invokeMethod($client, 'checkResponse', [$mock]);
    }

    public function testCheckResponseThrowsDataError()
    {
        $client = new Client('');

        $mock = $this->getMock('GuzzleHttp\Psr7\Response');
        $mock->expects($this->any())->method('getStatusCode')->will($this->returnValue('422'));
        $mock->expects($this->any())->method('getReasonPhrase')->will($this->returnValue('Unprocessable Entity'));

        $this->setExpectedException('Stanley\Geocodio\Exceptions\GeocodioDataError', 'Unprocessable Entity');

        $this->invokeMethod($client, 'checkResponse', [$mock]);
    }

    public function testCheckResponseThrowsServerError()
    {
        $client = new Client('');

        $mock = $this->getMock('GuzzleHttp\Psr7\Response');
        $mock->expects($this->any())->method('getStatusCode')->will($this->returnValue('500'));
        $mock->expects($this->any())->method('getReasonPhrase')->will($this->returnValue('Internal Server Error'));

        $this->setExpectedException('Stanley\Geocodio\Exceptions\GeocodioServerError', 'Internal Server Error');

        $this->invokeMethod($client, 'checkResponse', [$mock]);
    }

    public function testCheckResponseThrowsUnhandledError()
    {
        $client = new Client('');

        $mock = $this->getMock('GuzzleHttp\Psr7\Response');
        $mock->expects($this->any())->method('getStatusCode')->will($this->returnValue('404'));
        $mock->expects($this->any())->method('getReasonPhrase')->will($this->returnValue('Not Found'));

        $this->setExpectedException('Stanley\Geocodio\Exceptions\GeocodioException', 'Not Found');

        $this->invokeMethod($client, 'checkResponse', [$mock]);
    }
}