<?php

namespace Tests\Resources;

use Tests\TestCase;
use Shopify\Clients\Response;
use Shopify\Resources\Refund;
use Illuminate\Support\Collection;
use Tests\Concerns\MocksGuzzleResponse;
use Shopify\Models\Refund as RefundModel;
use Shopify\Contracts\Clients\HttpClient;

class RefundTest extends TestCase
{
    use MocksGuzzleResponse;

    /** @var HttpClient */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = \Mockery::mock(HttpClient::class)->shouldDeferMissing();

        $this->client->shouldReceive('execute')->andReturnUsing(function () {

            $this->createMockedGuzzleResponse([
                'refund' => [
                    'shipping' => [
                        'amount' => 5.00,
                    ],
                    'transactions' => [
                        [
                            'amount' => 11.95,
                        ],
                    ],
                ],
            ]);

            return new Response($this->mockedGuzzleResponse);
        })->byDefault();
    }

    /**
     * @group resource-tests
     * @group refund-tests
     *
     * @test
     */
    public function it_should_return_refund_calculation_responses_as_a_refund()
    {
        $refundResource = new Refund($this->client, '123456789');

        $refundModel = $refundResource->calculate([
            'shipping' => [
                'full_refund' => true,
            ],
        ]);

        $this->assertInstanceOf(RefundModel::class, $refundModel);

        $this->assertEquals([
            'shipping' => [
                'amount' => 5.00,
            ],
            'transactions' => [
                [
                    'amount' => 11.95,
                ],
            ],
        ], $refundModel->toArray());
    }

    /**
     * @group resource-tests
     * @group refund-tests
     *
     * @test
     */
    public function it_should_allow_refund_models_to_be_used_in_calculation()
    {
        $refundResource = new Refund($this->client, '123456789');

        $refundModel = new RefundModel([
            'shipping' => [
                'full_refund' => true,
            ],
        ]);

        $refundModelResponse = $refundResource->calculate($refundModel);

        $this->assertInstanceOf(RefundModel::class, $refundModelResponse);

        $this->assertEquals([
            'shipping' => [
                'amount' => 5.00,
            ],
            'transactions' => [
                [
                    'amount' => 11.95,
                ],
            ],
        ], $refundModelResponse->toArray());
    }

    /**
     * @group resource-tests
     * @group refund-tests
     *
     * @test
     */
    public function it_should_set_the_correct_model_on_the_resource()
    {
        $refundResource = new Refund($this->client, '123456789');

        $this->assertEquals(RefundModel::class, $refundResource->getModel());
    }
}
