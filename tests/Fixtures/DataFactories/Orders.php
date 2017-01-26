<?php

namespace \Tests\Fixtures\DataFactories;

use Incraigulous\DataFactories\DataFactory;

$faker = \Faker\Factory::create();

DataFactory::define('shopify-single-order-response', function() use ($faker) {

    $total_line_items_price = $faker->randomFloat(2, 0, 200);

    $subtotal_price = $total_line_items_price;

    $total_tax = $faker->randomFloat(2, 0, $subtotal_price*0.7);

    $total_discounts = $faker->randomFloat(2, 0, $subtotal_price*0.5);

    $total_price = $subtotal_price + $total_tax - $total_discounts;

    $order_number = $faker->randomNumber(2);

    $financial_status = $faker->randomElement(['pending', 'authorized', 'partially_paid', 'paid', 'partially_refunded', 'refunded', 'voided']);

    return [
        'order' => [
            'id' => $faker->randomNumber(8),
            'email' => $faker->safeEmail(),
            'closed_at' => $faker->boolean() ? $faker->iso8601() : null,
            'created_at' => $faker->iso8601(),
            'updated_at' => $faker->iso8601(),
            'number' => $order_number,
            'note' => $faker->paragraph(1,true),
            'token' => $faker->md5(),
            'gateway' => null,
            'test' => false,
            'total_price' => $total_price,
            'subtotal_price' => $subtotal_price,
            'total_weight' => $faker->randomInteger(1000), // in grams
            'total_tax' => $total_tax,
            'taxes_included' => $faker->boolean(),
            'currency' => 'USD',
            'financial_status' => $financial_status,
            'confirmed' => true,
            'total_discounts' => $total_discounts,
            'cart_token' => null,
            'buyer_accepts_marketing' => $faker->boolean(),
            'name' => '#' . ($order_number + 1000),
            'referring_site' => $faker->url(),
            'landing_site' => $faker->url(),
            'cancelled_at' => ($financial_status=='voided') ? $faker->iso8601() : null,
            'cancel_reason' => ($financial_status=='voided') ? $faker->randomElement(['customer_cancelled', 'fraud', 'inventory', 'other']) : null,
            'total_price_usd' => $total_price,
            'checkout_token' => null,
            'reference' => null,
            'user_id' => $faker->boolean() ? $faker->randomNumber(6) : null, // the shopify user who created the order
            'location_id' => null,
            'source_identifier' => null,
            'processed_at' => $faker->boolean() ? $faker->iso8601() : null,
            'device_id' => null,
            'browser_ip' => $faker->ipv4(),
            'landing_site_ref' => null,
            'order_number' => $number + 1000,
            'discount_codes' => [],
            'note_attributes' => [],
            'payment_gateway_names' => [],
            'processing_method' => 'manual',
            'checkout_id' => null,
            'source_name' => 'shopify_draft_order',
            'fulfillment_status' => $faker->randomElement([null, 'fulfilled', 'partial']),
            'tags' => "",
            "contact_email" => $faker->email(),
            "order_status_url" => $faker->url(),
            "line_items" => [],
        ]
    ];
});
