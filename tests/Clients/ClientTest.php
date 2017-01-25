<?php

namespace Tests\Clients;

use Shopify\Clients\Client;
use Shopify\Clients\Response;
use Shopify\Auth\Strategy\Strategy;
use Shopify\Auth\Strategy\HttpBasic;
use GuzzleHttp\Client as GuzzleClient;
use Tests\Concerns\MocksGuzzleResponse;
use Shopify\Auth\Config\StoreConfiguration;

class ShopifyClientTest extends \TestCase
{
    use MocksGuzzleResponse;

    /**
     * @group client-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_set_and_retrieve_store_configuration()
    {
        // mock credentials and no configuration
        $client = new Client(\Mockery::mock(Strategy::class), new StoreConfiguration(['store_name' => 'test']));

        $this->assertEquals([
            'store_name' => 'test'
        ], $client->getStoreConfiguration()->getConfiguration());

        $client->setStoreConfiguration(new StoreConfiguration(['store_name' => 'new-store']));

        $this->assertEquals('new-store', $client->getStoreConfiguration()->store_name);
    }

    /**
     * @group clients-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_create_a_guzzle_instance_on_initialization()
    {
        // mock credentials and no configuration
        $client = new Client(\Mockery::mock(Strategy::class), new StoreConfiguration(['store_name' => 'test']));

        $this->assertInstanceOf(GuzzleClient::class, $client->getHttpRequestor());
    }

    /**
     * @group client-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_build_a_host_name_from_store_name()
    {
        // mock credentials and no configuration
        $client = new Client(\Mockery::mock(Strategy::class), new StoreConfiguration(['store_name' => 'test']));

        $this->assertEquals('test.myshopify.com', $client->getHostFromStoreName());
    }

    /**
     * @group client-tests
     * @group clients-client-tests
     *
     * @test
     */
    public function it_should_return_a_response_object_on_execution()
    {
        $strategy = \Mockery::mock(Strategy::class);
        $strategy->shouldReceive('decorate')->andReturnUsing( function($req) {
            return $req;
        });

        // mock credentials and no configuration
        $client = new Client($strategy, new StoreConfiguration(['store_name' => 'test']));

        $requestor = \Mockery::mock(GuzzleClient::class);

        $requestor->shouldReceive('send')->andReturn($this->mockedGuzzleResponse);

        $client->setHttpRequestor($requestor);

        $requestor->shouldReceive('decorate')->andReturn();

        $response = $client->execute('GET', 'orders');

        $this->assertInstanceOf( Response::class, $response );
    }
}
