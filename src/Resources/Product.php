<?php

namespace Shopify\Resources;

use Shopify\Models\Product as ProductModel;

class Product extends Base
{
    protected $model = ProductModel::class;

    public function __construct($client)
    {
        parent::__construct($client, 'products');
    }
}
