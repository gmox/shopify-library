<?php

namespace Shopify\Resources;

use Shopify\Models\Model;
use Illuminate\Support\Collection;
use Shopify\Auth\Strategy\Strategy;
use Shopify\Auth\Config\StoreConfiguration;

class Base
{
    /** @var \Shopify\Contracts\Clients\HttpClient::class */
    protected $client;

    /** @var string */
    protected $resourceBase;

    /** @var string */
    protected $model;

    /**
     * Creates a Base object. This represents a RESTful resource on Shopify, and is used to provide a more RESTful interaction
     * with Shopify.
     *
     * @param \Shopify\Contracts\Clients\HttpClient::class  $client        The client that will be making the requests.
     * @param string                                        $resourceBase  The resource base that will be used for sending requsts.
     */
    public function __construct($client, $resourceBase)
    {
        $this->client = $client;

        $this->resourceBase = $resourceBase;
    }

    /**
     * Set the model to use for turning responses into Model instances.
     *
     * @param string  $model  The model that will be set.
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Create a request for the index of a resource.
     *
     * @param array  $queryParameters  The query parameters to be used in the request.
     */
    public function index(array $queryParameters = [])
    {
        $response = $this->client->execute('GET', $this->resourceBase, $queryParameters);

        return $this->toModelCollection($response);
    }

    /**
     * Create a resource.
     *
     * @param array  $data  The data to be used in the creation rquest.
     */
    public function create($data)
    {
        if( $data instanceof Model ) {
            $data = $data->toArray();
        }

        $response = $this->client->execute('POST', $this->resourceBase, [], [
            $this->singularResourceName() => $data
        ]);

        return $this->toModel($response);
    }

    /**
     * Find a resource by id.
     *
     * @param mixed  $model  The object to use for finding an entity (key, model instance, array)
     */
    public function find($model)
    {
        $key = $this->getKeyFromParameter($model);

        $response = $this->client->execute('GET', $this->resourceBase . '/' . $key);

        return $this->toModel($response);
    }

    /**
     * Update a resource
     *
     * @param mixed  $model  The object to use for updating an entity (key, model instance, array)
     */
    public function update($model)
    {
        $key = $this->getKeyFromParameter($model);

        if( $model instanceof Model ) {
            $model = $model->toArray();
        }

        $response = $this->client->execute('PUT', $this->resourceBase . '/' . $key, [], [
            $this->singularResourceName() => $model
        ]);

        return $this->toModel($response);
    }

    /**
     * Turn a response into an instance of a model.
     *
     * @param \Shopify\Clients\Response  $response  The response object to use
     */
    protected function toModel($response)
    {
        $model = $this->model;

        $data = $response->getResponseData();

        // shopify returns individual entities in the form of 'resource' => {object}
        $data = $data[$this->singularResourceName()];

        return new $model($data);
    }

    /**
     * Turn an array of resources into a Collection of model instances.
     *
     * @param \Shopify\Clients\Response  $response  The response object to use
     */
    protected function toModelCollection($response)
    {
        $collection = new Collection();

        $data = $response->getResponseData();

        $data = $data[$this->resourceBase];

        foreach( $data as $key => $resource ) {
            $model = $this->model;

            $collection[$key] = new $model($resource);
        }

        return $collection;
    }

    /**
     * Get the key of an external resource from a model, array or integer passed in.
     *
     * @param mixed  $param  The parameter to extract the external key from
     */
    protected function getKeyFromParameter($param)
    {
        $key = '';

        if( is_numeric($param) ) {
            $key = $param;
        } elseif( is_a($param, Model::class) ) {
            $key = $param->getKey();
        } elseif( is_array($param) ) {
            $key = $param['id'];
        }

        return $key;
    }

    /**
     * Turn the resource base into a singular word.
     *
     * @param string
     */
    protected function singularResourceName()
    {
        return str_singular($this->resourceBase);
    }
}
