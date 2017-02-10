<?php

namespace Shopify\Models;

class Order extends Model
{
    /** @var array */
    protected $relations = [
        'line_items'       => LineItem::class,
        'fulfillments'     => Fulfillment::class,
        'customer'         => Customer::class,
        'shipping_address' => Address::class,
        'billing_address'  => Address::class,
    ];

    /**
     * Determine if the order has been cancelled.
     *
     * @return boolean
     */
    public function isCancelled()
    {
        return is_null($this->cancelled_at);
    }

    /**
     * Determine if the order's payment is still pending.
     *
     * @return boolean
     */
    public function isPaymentPending()
    {
        return $this->financial_status == 'pending';
    }

    /**
     * Determine if the order has been completely fulfilled.
     *
     * @return boolean
     */
    public function isFulfilled()
    {
        return $this->fulfillment_status == 'fulfilled';
    }

    /**
     * Determine if the order has been partially fulfilled.
     *
     * @return boolean
     */
    public function isPartiallyFulfilled()
    {
        return $this->fulfillment_status == 'partial';
    }
}
