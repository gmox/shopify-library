<?php

namespace Tests\Models;

use Tests\TestCase;
use Shopify\Models\Order;

class OrderTest extends TestCase
{
    /**
     * @group model-tests
     * @group order-tests
     *
     * @test
     */
    public function it_should_return_false_for_non_cancelled_orders()
    {
        $model = new Order;

        $this->assertFalse($model->isCancelled());
    }

    /**
     * @group model-tests
     * @group order-tests
     *
     * @test
     */
    public function it_should_return_true_for_cancelled_orders()
    {
        $model = new Order([
            'cancelled_at' => date('c'),
        ]);

        $this->assertTrue($model->isCancelled());
    }

    /**
     * @group model-tests
     * @group order-tests
     *
     * @test
     */
    public function it_should_return_true_for_payment_pending_orders()
    {
        $model = new Order([
            'financial_status' => 'pending',
        ]);

        $this->assertTrue($model->isPaymentPending());
    }

    /**
     * @group model-tests
     * @group order-tests
     *
     * @test
     */
    public function it_should_return_false_for_paid_orders()
    {
        $model = new Order([
            'financial_status' => 'paid',
        ]);

        $this->assertFalse($model->isPaymentPending());
    }

    /**
     * @group model-tests
     * @group order-tests
     *
     * @test
     */
    public function it_should_return_false_for_refunded_orders()
    {
        $model = new Order([
            'financial_status' => 'refunded',
        ]);

        $this->assertFalse($model->isPaymentPending());
    }

    /**
     * @group model-tests
     * @group order-tests
     *
     * @test
     */
    public function it_should_return_true_for_fulfilled_orders()
    {
        $model = new Order([
            'fulfillment_status' => 'fulfilled',
        ]);

        $this->assertTrue($model->isFulfilled());
    }

    /**
     * @group model-tests
     * @group order-tests
     *
     * @test
     */
    public function it_should_return_true_for_unfulfilled_orders()
    {
        $model = new Order([
            'fulfillment_status' => null,
        ]);

        $this->assertFalse($model->isFulfilled());
    }

    /**
     * @group model-tests
     * @group order-tests
     *
     * @test
     */
    public function it_should_return_false_for_partially_fulfilled_orders()
    {
        $model = new Order([
            'fulfillment_status' => 'partial',
        ]);

        $this->assertFalse($model->isFulfilled());
    }

    /**
     * @group model-tests
     * @group order-tests
     *
     * @test
     */
    public function it_should_return_true_for_partially_fulfilled_orders()
    {
        $model = new Order([
            'fulfillment_status' => 'partial',
        ]);

        $this->assertTrue($model->isPartiallyFulfilled());
    }
}
