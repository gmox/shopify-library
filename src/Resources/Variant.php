<?php

namespace Shopify\Resources;

use Shopify\Models\Variant as VariantModel;

class Variant extends Base
{
    public function __construct($client)
    {
        parent::__construct($client, 'variants', VariantModel::class);
    }
}
