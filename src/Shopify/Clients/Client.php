<?php

namespace Shopify\Clients;

use GuzzleHttp\Psr7\Request;
use Shopify\Clients\Response;
use Shopify\Auth\Strategy\Strategy;
use GuzzleHttp\Client as GuzzleClient;
use Shopify\Contracts\Clients\HttpClient;
use Shopify\Auth\Config\StoreConfiguration;

class Client implements HttpClient
{
    /** @var GuzzleClient::class */
    protected $httpRequestor;

    /** @var array */
    protected $storeConfiguration;

    /** @var Strategy::class */
    protected $authenticationStrategy;

    /**
     * Create a Client object. This object can then be used to build and send requests, receive responses, utilize
     * authentication strategies.
     *
     * @param Strategy            $strategy            An instance of the auth strategy that will be used to authenticate the request
     * @param StoreConfiguration  $storeConfiguration  An instance of the store configuration will be used to build the request
     */
    public function __construct(Strategy $strategy, StoreConfiguration $storeConfiguration)
    {
        $this->setAuthenticationStrategy($strategy);

        $this->setStoreConfiguration($storeConfiguration);

        // create a guzzle instance that will be used as the requestor
        $this->setHttpRequestor( $this->createGuzzleInstance() );
    }

    public function setStoreConfiguration(StoreConfiguration $storeConfiguration)
    {
        $this->storeConfiguration = $storeConfiguration;
    }

    public function getStoreConfiguration()
    {
        return $this->storeConfiguration;
    }

    public function setHttpRequestor($httpRequestor)
    {
        $this->httpRequestor = $httpRequestor;
    }

    public function getHttpRequestor()
    {
        return $this->httpRequestor;
    }

    public function setAuthenticationStrategy(Strategy $strategy)
    {
        $this->authenticationStrategy = $strategy;
    }

    public function getAuthenticationStrategy()
    {
        return $this->authenticationStrategy;
    }

    /**
     * Get the name of the host from the store name. All Shopify requests follow this pattern.
     *
     * @return string
     */
    public function getHostFromStoreName()
    {
        $storeName = $this->getStoreConfiguration()->store_name;

        return $storeName . '.myshopify.com';
    }

    /**
     * Executes a request to an endpoint using the supplied parameters.
     *
     * @param string  $httpMethod       The HTTP Method of the request
     * @param string  $httpEndpoint     The endpoint of the request
     * @param array   $queryParameters  The query parameters to be used in the request
     * @param array   $data             The data that will be sent in the request body
     * @return Response  The response of the request
     */
    public function execute( $httpMethod, $httpEndpoint, array $queryParameters = [], array $data = [])
    {
        $response = [];

        $request = $this->buildGuzzleRequest($httpMethod, $httpEndpoint);

        $response = $this->httpRequestor->send($request,[
            'json'  => $data,
            'query' => $queryParameters
        ]);

        return new Response($response);
    }

    /**
     * Builds a guzzle request with the data we need. This will automatically append the admin root and the .json doctype
     * to the endpoint. It sets appropriate accept and content types to the required json. Additionally, it will decorate
     * the request with the authentication strategy.
     *
     * @param string  $httpMethod    The HTTP Method of the request
     * @param string  $httpEndpoint  The endpoint of the request
     * @return Request  The request that was built
     */
    protected function buildGuzzleRequest($httpMethod, $httpEndpoint)
    {
        $request = new Request($httpMethod, '/admin/' . $httpEndpoint . '.json', [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        // decorate the request with the proper authentication strategy
        $this->getAuthenticationStrategy()->decorate($request);

        return $request;
    }

    /**
     * Initialized an instance of the Guzzle Client. It sets the base_uri to the hostname generated from the StoreConfiguration
     *
     * @return GuzzleClient  The client that
     */
    protected function createGuzzleInstance()
    {
        return new GuzzleClient([
            'base_uri' => 'https://' . $this->getHostFromStoreName()
        ]);
    }
}
