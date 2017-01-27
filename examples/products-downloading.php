<?php

require 'vendor/autoload.php';

use Shopify\Clients\Client;
use Shopify\Resources\Product;
use Shopify\Auth\Strategy\HttpBasic;
use Shopify\Auth\Config\StoreConfiguration;

$strategy = new HttpBasic([
    'api_key'      => '57b4e927d7d876ded5108098adf7e22c',
    'api_password' => '7667c0b33a4fa00cc34e7afd4e0d9561'
]);

$configuration = new StoreConfiguration([
    'store_name' => 'smartstock-dev'
]);

$client = new Client($strategy, $configuration);

$productResource = new Product($client);

$products = $productResource->index(['limit' => 250]);

foreach( $products as $product ) {
    echo $product->title . PHP_EOL;
}
