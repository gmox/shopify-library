<?php

namespace Tests\Resources;

use Tests\TestCase;
use Shopify\Resources\Variant;
use Shopify\Contracts\Clients\HttpClient;
use Shopify\Models\Variant as VariantModel;

class VariantTest extends TestCase
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
        $variantResource = new Variant($this->client);

        $this->assertEquals(VariantModel::class, $variantResource->getModel());
    }
}
