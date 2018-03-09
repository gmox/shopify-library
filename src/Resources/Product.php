<?php

namespace Shopify\Resources;

use Shopify\Contracts\Clients\HttpClient;
use Shopify\Models\Product as ProductModel;

class Product extends Base
{
    /**
     * Product constructor.
     *
     * @param HttpClient  $client
     */
    public function __construct(HttpClient $client)
    {
        parent::__construct($client, 'products', ProductModel::class);
    }
}
