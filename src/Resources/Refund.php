<?php

namespace Shopify\Resources;

use Shopify\Models\Refund as RefundModel;

class Refund extends Base
{
    protected $model = RefundModel::class;

    public function __construct($client, $orderKey)
    {
        parent::__construct($client, 'orders/' . $orderKey . '/refunds');
    }

    /**
     * Calculate the refund using the data
     *
     * @param array  $data  The data to be used in the creation request.
     * @return Model
     */
    public function calculate($data)
    {
        if( $data instanceof Model ) {
            $data = $data->toArray();
        }

        $response = $this->client->execute('POST', $this->resourceBase, [], [
            $this->singularResourceName() => $data
        ]);

        return $this->toModel($response);
    }
}
