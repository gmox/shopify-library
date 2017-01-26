<?php

namespace Shopify\Models;

class Order extends Model
{
    protected $relations = [
        'line_items'   => LineItem::class,
        'fulfillments' => Fulfillment::class,
        'customer'     => Customer::class,
    ];

    public function isCancelled()
    {
        return is_null($this->cancelled_at);
    }

    public function isPaymentPending()
    {
        return $this->financial_status == 'pending';
    }

    public function isFulfilled()
    {
        return $this->fulfillment_status == 'fulfilled';
    }

    public function isPartiallyFulfilled()
    {
        return $this->fulfillment_status == 'partial';
    }

    public function getTotalPrice()
    {
        return $this->total_price;
    }

    public function getOrderName()
    {
        return $this->name;
    }

    public function getOrderNumber()
    {
        return $this->order_number;
    }
}
