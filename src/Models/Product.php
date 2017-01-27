<?php

namespace Shopify\Models;

class Product extends Model
{
    protected $relations = [
        'variants' => Variant::class,
        'images'   => Image::class,
    ];
}
