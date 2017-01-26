<?php

namespace Tests\Clients;

use Shopify\Clients\Response;
use Tests\Concerns\MocksGuzzleResponse;

class ResponseTest extends \TestCase
{
    use MocksGuzzleResponse;

    /** @var Response::class */
    protected $response;

    public function setUp()
    {
        parent::setUp();

        $this->createMockedGuzzleResponse();

        $this->response = new Response($this->mockedGuzzleResponse);
    }

    /**
     * @group clients-tests
     * @group clients-response-tests
     *
     * @test
     */
    public function it_should_build_api_throttling_from_response()
    {
        $this->assertEquals(5, $this->response->getRequestsMade());

        $this->assertEquals(40, $this->response->getRequestsMax());

        $this->assertEquals(35, $this->response->getRequestsRemaining());
    }

    /**
     * @group clients-tests
     * @group clients-response-tests
     *
     * @test
     */
    public function it_should_decode_and_set_json_response_body()
    {
        $this->assertEquals($this->mockResponseData, $this->response->getResponseData());
    }

    /**
     * @group clients-tests
     * @group clients-response-tests
     *
     * @test
     */
    public function it_should_return_the_response_code_from_the_response()
    {
        $this->assertEquals('200', $this->response->getResponseCode());
    }

    /**
     * @group clients-tests
     * @group clients-response-tests
     *
     * @test
     */
    public function it_should_return_the_original_response()
    {
        $this->assertEquals($this->mockedGuzzleResponse, $this->response->getShopifyResponse());
    }
}
