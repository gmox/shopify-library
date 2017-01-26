<?php

namespace Tests\Concerns;

use GuzzleHttp\Client;

trait MocksHttpRequest
{
    public function mockHttpRequestor()
    {
        $requestor = \Mockery::mock(Client::class);

        $requestor->shouldReceive('send')->andReturn($this->mockedGuzzleResponse);

        $requestor->shouldReceive('decorate')->andReturn();

        return $requestor;
    }
}
