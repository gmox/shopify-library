<?php

namespace Shopify\Resources;

use Shopify\Models\Refund as RefundModel;

class Refund extends Base
{
    public function __construct($client, $orderKey)
    {
        parent::__construct($client, 'orders/' . $orderKey . '/refunds', RefundModel::class);
    }

    /**
     * Calculate the refund using the data
     *
     * @param array  $data  The data to be used in the creation request.
     * @return RefundModel
     */
    public function calculate($data) : RefundModel
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
