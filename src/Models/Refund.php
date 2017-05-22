<?php

namespace Shopify\Models;

class Refund extends Model
{
    protected $relations = [
        'transactions' => Transaction::class,
    ];
}
