<?php

class DataTest extends BaseTest
{
    /**
     * @test
     */
    public function testConstructorSetsProperty()
    {
        $data = new stdClass();
        $data->test = 'test';
        $data->item = 'item';

        $json = json_encode($data);

        $mock = new Stanley\Geocodio\Data($json);
        $this->assertEquals($mock->response, $data);
    }
}