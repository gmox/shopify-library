<?php

namespace Shopify\Resources;

use Shopify\Clients\Response;
use Shopify\Models\Metafield;
use Shopify\Models\Model;
use Illuminate\Support\Collection;
use Shopify\Contracts\Clients\HttpClient;

class Base
{
    /** @var HttpClient */
    protected $client;

    /** @var string */
    protected $resourceBase;

    /** @var string */
    protected $model;

    /**
     * Creates a Base object. This represents a RESTful resource on Shopify, and is used to provide a more RESTful interaction
     * with Shopify.
     *
     * @param HttpClient  $client        The client that will be making the requests.
     * @param string      $resourceBase  The resource base that will be used for sending requsts.
     * @param string      $model         The model type that the base will use for requests.
     */
    public function __construct(HttpClient $client, string $resourceBase, string $model = Model::class)
    {
        $this->client = $client;
        $this->resourceBase = $resourceBase;
        $this->model = $model;
    }

    /**
     * Set the model to use for turning responses into Model instances.
     *
     * @param string  $model  The model that will be set.
     */
    public function setModel(string $model)
    {
        $this->model = $model;
    }

    /**
     * Get the model to use for turning responses into Model instances.
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Create a request for the index of a resource.
     *
     * @param array  $queryParameters  The query parameters to be used in the request.
     * @return Collection
     */
    public function index(array $queryParameters = []): Collection
    {
        $response = $this->client->execute('GET', $this->resourceBase, $queryParameters);

        return $this->toModelCollection($response);
    }

    /**
     * Create a request for the count of a resource.
     *
     * @param array  $queryParameters  The query parameters to be used in the request.
     * @return Model
     */
    public function count(array $queryParameters = []): Model
    {
        $response = $this->client->execute('GET', $this->resourceBase . '/count', $queryParameters);

        // Shopify count responses aren't
        return new Model($response->getResponseData());
    }

    /**
     * Create a resource.
     *
     * @param mixed  $data  The data to be used in the creation request.
     * @return Model
     */
    public function create($data): Model
    {
        if ($data instanceof Model) {
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
     * @return Model
     */
    public function find($model): Model
    {
        $key = $this->getKeyFromParameter($model);

        $response = $this->client->execute('GET', $this->resourceBase . '/' . $key);

        return $this->toModel($response);
    }

    /**
     * Update a resource
     *
     * @param mixed  $model  The object to use for updating an entity (key, model instance, array)
     * @return Model
     */
    public function update($model): Model
    {
        $key = $this->getKeyFromParameter($model);

        if ($model instanceof Model) {
            $model = $model->toArray();
        }

        $response = $this->client->execute('PUT', $this->resourceBase . '/' . $key, [], [
            $this->singularResourceName() => $model
        ]);

        return $this->toModel($response);
    }

    /**
     * Find all metafields of a resource
     *
     * @param mixed  $model  The object to use for finding an entity (key, model instance, array)
     * @return Collection
     */
    public function metafields($model): Collection
    {
        $key = $this->getKeyFromParameter($model);

        $response = $this->client->execute('GET', $this->resourceBase . '/' . $key . '/metafields');

        return $this->toMetafieldCollection($response);
    }

    /**
     * Turn a response into an instance of a model.
     *
     * @param Response  $response  The response object to use
     * @return Model
     */
    protected function toModel(Response $response): Model
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
     * @param Response  $response  The response object to use
     * @return Collection
     */
    protected function toModelCollection(Response $response): Collection
    {
        $collection = new Collection();

        $data = $response->getResponseData();

        $data = $data[$this->pluralResourceName()];

        foreach ($data as $key => $resource) {
            $model = $this->model;

            $collection[$key] = new $model($resource);
        }

        return $collection;
    }

    /**
     * Turn an array of metafields into a Collection of Metafield instances.
     *
     * @param Response  $response  The response object to use
     * @return Collection
     */
    protected function toMetafieldCollection($response): Collection
    {
        $collection = new Collection();

        $data = $response->getResponseData();

        $data = $data['metafields'];

        foreach ($data as $key => $resource) {
            $model = new Metafield();

            $collection[$key] = new $model($resource);
        }

        return $collection;
    }

    /**
     * Get the key of an external resource from a model, array or integer passed in.
     *
     * @param mixed  $param  The parameter to extract the external key from
     * @return string
     */
    protected function getKeyFromParameter($param): string
    {
        $key = '';

        if (is_numeric($param)) {
            $key = $param;
        } elseif (is_a($param, Model::class)) {
            $key = $param->getKey();
        } elseif (is_array($param)) {
            $key = $param['id'];
        }

        return $key;
    }

    /**
     * Use the short name of this class to determine what should be extracted from the API response.
     *
     * @return string
     */
    protected function singularResourceName(): string
    {
        $reflect = new \ReflectionClass(static::class);

        $resourceName = strtolower($reflect->getShortName());

        return str_singular($resourceName);
    }

    /**
     * Use the short name of this class to determine what should be extracted from the API response.
     *
     * @return string
     */
    protected function pluralResourceName(): string
    {
        $reflect = new \ReflectionClass(static::class);

        $resourceName = strtolower($reflect->getShortName());

        return str_plural($resourceName);
    }
}
