<?php

namespace Shopify\Resources;

use Shopify\Models\Order as OrderModel;

class Order extends Base
{
    public function __construct($client)
    {
        parent::__construct($client, 'orders', OrderModel::class);
    }
}
