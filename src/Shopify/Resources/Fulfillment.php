<?php

namespace Shopify\Resources;

use Shopify\Models\Fulfillment as FulfillmentModel;

class Fulfillment extends Base
{
    protected $model = FulfillmentModel::class;

    public function __construct($client, $orderKey)
    {
        parent::__construct($client, 'orders/' . $orderKey);
    }
}
