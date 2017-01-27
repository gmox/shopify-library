<?php

namespace Tests\Concerns;

use GuzzleHttp\Psr7\Response;

trait MocksGuzzleResponse
{
    protected $mockedGuzzleResponse;

    protected $mockResponseData;

    public function setUp()
    {
        parent::setUp();

        $this->createMockedGuzzleResponse();
    }

    public function createMockedGuzzleResponse($responseDataToUse = [])
    {
        if (empty($responseDataToUse)) {
            $responseDataToUse = [
                'id' => 1,
                'key' => 'value',
                'otherKey' => [
                    'nested' => 'value'
                ]
            ];
        }
        // some random data
        $this->mockResponseData = $responseDataToUse;

        $this->mockedGuzzleResponse = \Mockery::mock(Response::class);

        // guzzle returns the JSON in its string form
        $this->mockedGuzzleResponse->shouldReceive('getBody')->andReturn(json_encode($this->mockResponseData))->byDefault();

        $this->mockedGuzzleResponse->shouldReceive('getStatusCode')->andReturn('200')->byDefault();

        $this->mockedGuzzleResponse->shouldReceive('getHeader')->with('X-Shopify-Shop-Api-Call-Limit')->andReturn(['5/40'])->byDefault();
    }
}
