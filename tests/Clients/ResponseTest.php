<?php

namespace Tests\Clients;

use Shopify\Clients\Response;
use Tests\Concerns\MocksGuzzleResponse;

class ResponseTest extends \TestCase
{
    use MocksGuzzleResponse;

    /**
     * @group clients-tests
     * @group clients-response-tests
     *
     * @test
     */
    public function it_should_build_api_throttling_from_response()
    {
        $response = new Response($this->mockedGuzzleResponse);

        $this->assertEquals(5, $response->getRequestsMade());

        $this->assertEquals(40, $response->getRequestsMax());

        $this->assertEquals(35, $response->getRequestsRemaining());
    }

    /**
     * @group clients-tests
     * @group clients-response-tests
     *
     * @test
     */
    public function it_should_decode_and_set_json_response_body()
    {
        $response = new Response($this->mockedGuzzleResponse);

        $this->assertEquals($this->mockResponseData, $response->getResponseData());
    }

    /**
     * @group clients-tests
     * @group clients-response-tests
     *
     * @test
     */
    public function it_should_return_the_response_code_from_the_response()
    {
        $response = new Response($this->mockedGuzzleResponse);

        $this->assertEquals('200', $response->getResponseCode());
    }
}
