<?php

namespace Shopify\Resources;

use Shopify\Contracts\Clients\HttpClient;
use Shopify\Models\Variant as VariantModel;

class Variant extends Base
{
    /**
     * Variant constructor.
     *
     * @param HttpClient  $client
     */
    public function __construct(HttpClient $client)
    {
        parent::__construct($client, 'variants', VariantModel::class);
    }
}
