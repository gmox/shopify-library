<?php

namespace Shopify\Resources;

use Shopify\Contracts\Clients\HttpClient;
use Shopify\Models\Fulfillment as FulfillmentModel;

class Fulfillment extends Base
{
    /**
     * Fulfillment constructor.
     *
     * @param HttpClient  $client
     * @param string      $orderKey
     */
    public function __construct(HttpClient $client, string $orderKey)
    {
        parent::__construct($client, 'orders/' . $orderKey . '/fulfillments', FulfillmentModel::class);
    }
}
