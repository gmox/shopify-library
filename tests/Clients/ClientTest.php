<?php

namespace Tests\Clients;

use Tests\TestCase;
use Shopify\Clients\Client;
use Shopify\Clients\Response;
use Shopify\Auth\Strategy\Strategy;
use Tests\Concerns\MocksHttpRequest;
use GuzzleHttp\Client as GuzzleClient;
use Tests\Concerns\MocksGuzzleResponse;
use Shopify\Auth\Config\StoreConfiguration;

class ClientTest extends TestCase
{
    use MocksGuzzleResponse,
        MocksHttpRequest;

    protected $client;

    public function setUp()
    {
        parent::setup();

        $strategy = \Mockery::mock(Strategy::class);
        $strategy->shouldReceive('decorate')->andReturn();

        // mock credentials
        $this->client = new Client($strategy, new StoreConfiguration(['store_name' => 'test']));

        $this->createMockedGuzzleResponse();
    }

    /**
     * @group client-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_set_and_retrieve_store_configuration()
    {
        $this->assertEquals([
            'store_name' => 'test'
        ], $this->client->getStoreConfiguration()->getConfiguration());

        $this->client->setStoreConfiguration(new StoreConfiguration(['store_name' => 'new-store']));

        $this->assertEquals('new-store', $this->client->getStoreConfiguration()->store_name);
    }

    /**
     * @group clients-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_create_a_guzzle_instance_on_initialization()
    {
        $this->assertInstanceOf(GuzzleClient::class, $this->client->getHttpRequestor());
    }

    /**
     * @group client-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_build_a_host_name_from_store_name()
    {
        $this->assertEquals('test.myshopify.com', $this->client->getHostFromStoreName());
    }

    /**
     * @group client-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_return_a_response_object_on_execution()
    {
        $requestor = $this->mockHttpRequestor();

        $this->client->setHttpRequestor($requestor);

        $response = $this->client->execute('GET', 'resource');

        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @group client-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_throw_an_exception_for_not_found_resources()
    {
        $this->expectExceptionMessage('The resource you are trying to access does not exist.');

        $this->mockedGuzzleResponse->shouldReceive('getStatusCode')->andReturn('404');

        $requestor = $this->mockHttpRequestor();

        $this->client->setHttpRequestor($requestor);

        $this->client->execute('GET', 'resource');
    }

    /**
     * @group client-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_throw_an_exception_for_invalid_api_credentials()
    {
        $this->expectExceptionMessage('[API] Invalid Username provided for Basic Auth API access.');

        $this->mockedGuzzleResponse->shouldReceive('getStatusCode')->andReturn('403');
        $this->mockedGuzzleResponse->shouldReceive('getBody')->andReturn(json_encode([
            'errors' => '[API] Invalid Username provided for Basic Auth API access.'
        ]));

        $requestor = $this->mockHttpRequestor();

        $this->client->setHttpRequestor($requestor);

        $this->client->execute('GET', 'resource');
    }

    /**
     * @group client-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_throw_an_exception_for_unprocessable_exceptions()
    {
        $this->expectExceptionMessage('Required parameter missing or invalid');

        $this->mockedGuzzleResponse->shouldReceive('getStatusCode')->andReturn('400');
        $this->mockedGuzzleResponse->shouldReceive('getBody')->andReturn(json_encode([
            'errors' => [
                'product' => 'Required parameter missing or invalid'
            ]
        ]));

        $requestor = $this->mockHttpRequestor();

        $this->client->setHttpRequestor($requestor);

        $this->client->execute('GET', 'resource');
    }

    /**
     * @group client-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_throw_an_exception_for_server_errors()
    {
        $this->expectExceptionMessage('Shopify is experiencing technical issues at the moment. Please try again later.');

        $this->mockedGuzzleResponse->shouldReceive('getStatusCode')->andReturn('500');

        $requestor = $this->mockHttpRequestor();

        $this->client->setHttpRequestor($requestor);

        $this->client->execute('GET', 'resource');
    }
}
