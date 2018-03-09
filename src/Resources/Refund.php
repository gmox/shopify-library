<?php

namespace Shopify\Resources;

use Shopify\Contracts\Clients\HttpClient;
use Shopify\Models\Refund as RefundModel;

class Refund extends Base
{
    /**
     * Refund constructor.
     *
     * @param HttpClient  $client
     * @param string      $orderKey
     */
    public function __construct(HttpClient $client, string $orderKey)
    {
        parent::__construct($client, 'orders/' . $orderKey . '/refunds', RefundModel::class);
    }

    /**
     * Calculate the refund using the data
     *
     * @param mixed  $data  The data to be used in the creation request.
     * @return RefundModel
     */
    public function calculate($data): RefundModel
    {
        if( $data instanceof RefundModel ) {
            $data = $data->toArray();
        }

        $response = $this->client->execute('POST', $this->resourceBase, [], [
            $this->singularResourceName() => $data
        ]);

        return $this->toModel($response);
    }
}
