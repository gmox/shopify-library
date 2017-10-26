<?php

require 'vendor/autoload.php';

use Shopify\Clients\Client;
use Shopify\Resources\Product;
use Shopify\Auth\Strategy\HttpBasic;
use Shopify\Auth\Config\StoreConfiguration;

$strategy = new HttpBasic([
    'api_key'      => '',
    'api_password' => ''
]);

$configuration = new StoreConfiguration([
    'store_name' => ''
]);

$client = new Client($strategy, $configuration);

$productResource = new Product($client);

$products = $productResource->index(['limit' => 250]);

foreach( $products as $product ) {
    echo $product->title . PHP_EOL;
}
