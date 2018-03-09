<?php

namespace Shopify\Resources;

use Shopify\Contracts\Clients\HttpClient;
use Shopify\Models\Order as OrderModel;

class Order extends Base
{
    /**
     * Order constructor.
     *
     * @param HttpClient  $client
     */
    public function __construct(HttpClient $client)
    {
        parent::__construct($client, 'orders', OrderModel::class);
    }
}
