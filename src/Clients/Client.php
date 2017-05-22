<?php

namespace Shopify\Clients;

use GuzzleHttp\Psr7\Request;
use Shopify\Clients\Response;
use Shopify\Auth\Strategy\Strategy;
use GuzzleHttp\Client as GuzzleClient;
use Shopify\Contracts\Clients\HttpClient;
use Shopify\Auth\Config\StoreConfiguration;
use Shopify\Clients\Exceptions\ServerErrorException;
use Shopify\Clients\Exceptions\ResourceNotFoundException;
use Shopify\Clients\Exceptions\ResourceRejectedException;
use Shopify\Clients\Exceptions\InvalidCredentialsException;

class Client implements HttpClient
{
    /** @var GuzzleClient */
    protected $httpRequestor;

    /** @var array */
    protected $storeConfiguration;

    /** @var Strategy */
    protected $authenticationStrategy;

    /**
     * Create a Client object. This object can then be used to build and send requests, receive responses, utilize
     * authentication strategies.
     *
     * @param Strategy            $strategy            An instance of the auth strategy that will be used to authenticate the request.
     * @param StoreConfiguration  $storeConfiguration  An instance of the store configuration will be used to build the request.
     */
    public function __construct(Strategy $strategy, StoreConfiguration $storeConfiguration)
    {
        $this->setAuthenticationStrategy($strategy);

        $this->setStoreConfiguration($storeConfiguration);

        // create a guzzle instance that will be used as the requestor
        $this->setHttpRequestor( $this->createGuzzleInstance() );
    }

    /**
     * Set the store configuration to be used in requests.
     *
     * @param StoreConfiguration  $storeConfiguration  An instance of the store configuration will be used to build the request.
     */
    public function setStoreConfiguration(StoreConfiguration $storeConfiguration)
    {
        $this->storeConfiguration = $storeConfiguration;
    }

    /**
     * Get the store configuration being used in requests.
     *
     * @return StoreConfiguration
     */
    public function getStoreConfiguration() : StoreConfiguration
    {
        return $this->storeConfiguration;
    }

    /**
     * Set the object that will execute HTTP requests.
     *
     * @param mixed  $httpRequestor  The requester that executes HTTP rquests
     */
    public function setHttpRequestor($httpRequestor)
    {
        $this->httpRequestor = $httpRequestor;
    }

    /**
     * Get the object that will execute HTTP requests.
     *
     * @return mixed
     */
    public function getHttpRequestor()
    {
        return $this->httpRequestor;
    }

    /**
     * Set the authentication strategy that will decorate HTTP requests with proper authentication.
     *
     * @param Strategy  $strategy  The strategy that will be used in the HTTP request.
     */
    public function setAuthenticationStrategy(Strategy $strategy)
    {
        $this->authenticationStrategy = $strategy;
    }

    /**
     * Get the authentication strategy that will decorate HTTP requests with proper authentication.
     *
     * @return Strategy The strategy that will be used in the HTTP request.
     */
    public function getAuthenticationStrategy() : Strategy
    {
        return $this->authenticationStrategy;
    }

    /**
     * Get the name of the host from the store name. All Shopify requests follow this pattern.
     *
     * @return string
     */
    public function getHostFromStoreName() : string
    {
        $storeName = $this->getStoreConfiguration()->store_name;

        return $storeName . '.myshopify.com';
    }

    /**
     * Executes a request to an endpoint using the supplied parameters.
     *
     * @param string  $httpMethod       The HTTP Method of the request
     * @param string  $httpEndpoint     The resource endpoint of the request
     * @param array   $queryParameters  The query parameters to be passed in the request
     * @param array   $data             The data that will be sent in the request body as JSON data.
     * @return Response  The response of the request
     * @throws \Exception
     */
    public function execute( $httpMethod, $httpEndpoint, array $queryParameters = [], array $data = []) : Response
    {
        $request = $this->buildGuzzleRequest($httpMethod, $httpEndpoint);

        $response = $this->httpRequestor->send($request, [
            'json'        => $data,
            'query'       => $queryParameters,
            'http_errors' => false // we'll handle the errors on our end
        ]);

        if( $response->getStatusCode() != 200 && $response->getStatusCode() != 201 )
        {
            $this->throwExceptionFromInvalidRequest($response);
        }

        return new Response($response);
    }

    /**
     * Builds a guzzle request with the data we need. This will automatically append the .json doctype to the endpoint.
     * It sets appropriate accept and content types to the required JSON. Additionally, it will decorate the request with
     * the authentication strategy.
     *
     * @param string  $httpMethod    The HTTP Method of the request
     * @param string  $httpEndpoint  The endpoint of the request
     * @return Request  The request that was built
     */
    protected function buildGuzzleRequest($httpMethod, $httpEndpoint) : Request
    {
        $request = new Request($httpMethod, $httpEndpoint . '.json', [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        // decorate the request with the proper authentication strategy
        $this->getAuthenticationStrategy()->decorate($request);

        return $request;
    }

    /**
     * Initialized an instance of the Guzzle Client. It sets the base_uri to the store URL passed in the StoreConfiguration.
     *
     * @return GuzzleClient  The client that will be making the requests.
     */
    protected function createGuzzleInstance() : GuzzleClient
    {
        return new GuzzleClient([
            'base_uri' => 'https://' . $this->getHostFromStoreName() . '/admin/'
        ]);
    }

    /**
     * In the event of a response indicating an invalid request (or server error), this will decode the response body to
     * get the error message, find the appropriate exception to throw based on the response code, and then throw that exception.
     *
     * @param \GuzzleHttpPsr7\Response  $response  The response from the server.
     * @throws \Exception
     */
    protected function throwExceptionFromInvalidRequest($response)
    {
        $data = json_decode($response->getBody(), true);

        $errorMessage = $this->parseResponseForErrorMessage($data);

        $exception = $this->getExceptionFromResponseCode($response->getStatusCode(), $errorMessage);

        throw $exception;
    }

    /**
     * Parse the response and get the error message from it.
     *
     * @param array  $data  The decoded data from the response.
     * @return string
     */
    protected function parseResponseForErrorMessage($data) : string
    {
        // default to a generic error message
        $errorMessage = 'An error occured in your request.';

        if( isset($data['errors']) )
        {
            $errors = $data['errors'];

            if( is_array($errors) ) {
                $errorMessage = array_pop($errors);
            } else {
                $errorMessage = $errors;
            }
        }

        return $errorMessage;
    }

    /**
     * Depending on the response code, we want to throw different exceptions to the user. Additionally, Shopify's default
     * error messages can be terse and unhelpful, so for those we will return more helpful messages.
     *
     * @param string  $responseCode  The response code from the server.
     * @param string  $errorMessage  The error message we want to pass to the exception.
     * @return \Exception
     */
    protected function getExceptionFromResponseCode($responseCode, $errorMessage = '') : \Exception
    {
        $exception = new \Exception($errorMessage);

        // check if it failed
        if( $responseCode == 400 ) {
            $exception = new ResourceRejectedException($errorMessage);
        } elseif( $responseCode == 403 ) {
            $exception = new InvalidCredentialsException($errorMessage);
        } elseif( $responseCode == 404 ) {
            $exception = new ResourceNotFoundException('The resource you are trying to access does not exist.');
        } if( $responseCode == 500 ) {
            $exception = new ServerErrorException('Shopify is experiencing technical issues at the moment. Please try again later.');
        }

        return $exception;
    }
}
