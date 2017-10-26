<?php

namespace Shopify\Models;

class Order extends Model
{
    /** @var array */
    protected $relations = [
        'line_items'       => LineItem::class,
        'shipping_lines'   => ShippingLine::class,
        'fulfillments'     => Fulfillment::class,
        'customer'         => Customer::class,
        'shipping_address' => Address::class,
        'billing_address'  => Address::class,
        'refunds'          => Refund::class,
        'transactions'     => Transaction::class,
    ];

    /**
     * Determine if the order has been cancelled.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return !is_null($this->cancelled_at);
    }

    /**
     * Determine if the order's payment is still pending.
     *
     * @return bool
     */
    public function isPaymentPending(): bool
    {
        return $this->financial_status == 'pending';
    }

    /**
     * Determine if the order has been completely fulfilled.
     *
     * @return bool
     */
    public function isFulfilled(): bool
    {
        return $this->fulfillment_status == 'fulfilled';
    }

    /**
     * Determine if the order has been partially fulfilled.
     *
     * @return bool
     */
    public function isPartiallyFulfilled(): bool
    {
        return $this->fulfillment_status == 'partial';
    }
}
