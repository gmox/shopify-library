<?php

require 'vendor/autoload.php';

use Shopify\Clients\Client;
use Shopify\Resources\Order;
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

$orderResource = new Order($client);

// get al paid, unfulfilled orders
$orders = $orderResource->index([
    'financial_status'   => 'paid',
    'fulfillment_status' => null
]);

foreach( $orders as $order ) {
    echo $order->name . ' - ' . $order->total_price . PHP_EOL;
}
