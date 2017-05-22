<?php

namespace Tests\Resources;

use Tests\TestCase;
use Shopify\Resources\Product;
use Shopify\Contracts\Clients\HttpClient;
use Shopify\Models\Product as ProductModel;

class ProductTest extends TestCase
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
     * @group product-tests
     *
     * @test
     */
    public function it_should_set_the_correct_model_on_the_resource()
    {
        $productResource = new Product($this->client);

        $this->assertEquals(ProductModel::class, $productResource->getModel());
    }
}
