<?php

namespace Shopify\Resources;

use Shopify\Models\Customer as CustomerModel;

class Customer extends Base
{
    protected $model = CustomerModel::class;

    public function __construct($client)
    {
        parent::__construct($client, 'customers');
    }
}
