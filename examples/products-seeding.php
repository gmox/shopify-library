<?php

require 'vendor/autoload.php';

use Shopify\Clients\Client;
use Shopify\Resources\Product;
use Shopify\Auth\Strategy\HttpBasic;
use Shopify\Auth\Config\StoreConfiguration;

$strategy = new HttpBasic([
    'api_key'      => '589b2c309febf0655f8f04ffee947603',
    'api_password' => 'ef0a2b3b633c77b9a2769e4758fa809a'
]);

$configuration = new StoreConfiguration([
    'store_name' => 'sportify-dev'
]);

$client = new Client($strategy, $configuration);

$productResource = new Product($client);

for( $i = 0; $i < 38; $i++ ) {
    $faker = \Faker\Factory::create();

    $product = $productResource->create([
        'title'        => ucwords($faker->words( rand(4,8), true )),
        'body_html'    => $faker->paragraph( rand(4,8), true ),
        'product_type' => $faker->words( rand(2,3), true ),
        'vendor'       => $faker->company(),
        'images'       => [
            [
                'src'  => $faker->imageUrl(800, 1200, 'sports')
            ]
        ],
        'variants'     => [
            [
                'option1'              => $faker->words( rand(1,2), true),
                'price'                => $faker->randomFloat( 2, 0, 50.00 ),
                'sku'                  => $faker->ean8(),
                'inventory_management' => 'shopify',
                'inventory_quantity'   => $faker->boolean() ? $faker->randomNumber(2) : $faker->randomNumber(1)
            ]
        ]
    ]);
    var_dump($product->toArray());
}
