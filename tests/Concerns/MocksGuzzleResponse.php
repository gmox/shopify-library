<?php

namespace Tests\Concerns;

use GuzzleHttp\Psr7\Response;

trait MocksGuzzleResponse
{
    protected $mockedGuzzleResponse;

    public function setUp()
    {
        parent::setUp();

        // some random data
        $this->mockResponseData = [
            'key' => 'value',
            'otherKey' => [
                'nested' => 'value'
            ]
        ];

        $this->mockedGuzzleResponse = \Mockery::mock(Response::class);

        // guzzle returns the JSON in its string form
        $this->mockedGuzzleResponse->shouldReceive('getBody')->andReturn(json_encode($this->mockResponseData));

        $this->mockedGuzzleResponse->shouldReceive('getStatusCode')->andReturn('200');

        $this->mockedGuzzleResponse->shouldReceive('getHeader')->with('X-Shopify-Shop-Api-Call-Limit')->andReturn(['5/40']);

    }
}
