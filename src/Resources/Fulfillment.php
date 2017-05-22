<?php

namespace Shopify\Resources;

use Shopify\Models\Fulfillment as FulfillmentModel;

class Fulfillment extends Base
{
    public function __construct($client, $orderKey)
    {
        parent::__construct($client, 'orders/' . $orderKey . '/fulfillments', FulfillmentModel::class);
    }
}
