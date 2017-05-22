<?php

namespace Shopify\Resources;

use Shopify\Models\Product as ProductModel;

class Product extends Base
{
    public function __construct($client)
    {
        parent::__construct($client, 'products', ProductModel::class);
    }
}
