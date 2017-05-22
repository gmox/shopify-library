<?php

namespace Tests\Resources;

use Tests\TestCase;
use Shopify\Resources\Customer;
use Shopify\Contracts\Clients\HttpClient;
use Shopify\Models\Customer as CustomerModel;

class CustomerTest extends TestCase
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
     * @group customer-tests
     *
     * @test
     */
    public function it_should_set_the_correct_model_on_the_resource()
    {
        $customerResource = new Customer($this->client);

        $this->assertEquals(CustomerModel::class, $customerResource->getModel());
    }
}
