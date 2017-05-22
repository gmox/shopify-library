<?php

namespace Tests\Resources;

use Tests\TestCase;
use Shopify\Resources\Order;
use Shopify\Models\Order as OrderModel;
use Shopify\Contracts\Clients\HttpClient;

class OrderTest extends TestCase
{
    /** @var HttpClient */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = \Mockery::mock(HttpClient::class)->shouldDeferMissing();
    }

    /**
     * @group resource-tests
     * @group order-tests
     *
     * @test
     */
    public function it_should_set_the_correct_model_on_the_resource()
    {
        $orderResource = new Order($this->client);

        $this->assertEquals(OrderModel::class, $orderResource->getModel());
    }
}
