<?php

namespace Tests\Resources;

use Tests\TestCase;
use Shopify\Resources\Fulfillment;
use Shopify\Contracts\Clients\HttpClient;
use Shopify\Models\Fulfillment as FulfillmentModel;

class FulfillmentTest extends TestCase
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
     * @group fulfillment-tests
     *
     * @test
     */
    public function it_should_set_the_correct_model_on_the_resource()
    {
        $fulfillmentResource = new Fulfillment($this->client, '1234567890');

        $this->assertEquals(FulfillmentModel::class, $fulfillmentResource->getModel());
    }
}
