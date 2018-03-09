<?php

namespace Shopify\Resources;

use Shopify\Contracts\Clients\HttpClient;
use Shopify\Models\Customer as CustomerModel;

class Customer extends Base
{
    /**
     * Customer constructor.
     *
     * @param HttpClient  $client
     */
    public function __construct(HttpClient $client)
    {
        parent::__construct($client, 'customers', CustomerModel::class);
    }
}
