<?php

namespace Shopify\Clients;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response
{
    /** @var GuzzleResponse */
    protected $shopifyResponse;

    /** @var int */
    protected $requestsMax;

    /** @var int */
    protected $requestsMade;

    /** @var array */
    protected $responseData;

    /**
     * Create a Response object. This performs actions on the GuzzleResponse object turned by the Client, including parsing
     * and returning the throttle limits.
     *
     * @param GuzzleResponse  $shopifyResponse  GuzzleResponse returned by the request execution
     */
    public function __construct($shopifyResponse)
    {
        $this->setShopifyResponse($shopifyResponse);

        $this->setThrottleLimitsFromResponse();

        $this->setResponseDataFromResponse();
    }

    /**
     * Set the response from Shopify
     *
     * @param GuzzleResponse  $shopifyResponse  GuzzleResponse returned by the request execution
     */
    public function setShopifyResponse(GuzzleResponse $shopifyResponse)
    {
        $this->shopifyResponse = $shopifyResponse;
    }

    /**
     * Return the response from Shopify
     *
     * @return GuzzleResponse GuzzleResponse returned by the request execution
     */
    public function getShopifyResponse() : GuzzleResponse
    {
        return $this->shopifyResponse;
    }

    /**
     * Get the total requests remaining (calculated by max requests - requests made)
     *
     * @return integer  The total requests remaining
     */
    public function getRequestsRemaining() : int
    {
        return $this->getRequestsMax() - $this->getRequestsMade();
    }

    /**
     * Get the total requests made
     *
     * @return int  The total requests made
     */
    public function getRequestsMade() : int
    {
        return $this->requestsMade;
    }

    /**
     * Get the maximum requests allowed by Shopify
     *
     * @return int  The maximum requests allowed
     */
    public function getRequestsMax() : int
    {
        return $this->requestsMax;
    }

    /**
     * Get the HTTP response code from the request
     *
     * @return string  The HTTP response code
     */
    public function getResponseCode() : string
    {
        return $this->shopifyResponse->getStatusCode();
    }

    /**
     * Get the parsed HTTP response data from the request
     *
     * @return array  The decoded response data
     */
    public function getResponseData() : array
    {
        return $this->responseData;
    }

    /**
     * Shopify throttles requests through a method called "leaky bucket." You are allowed to make only so many requests
     * within a certain amount of time until Shopify will throttle your requests. For basic plans, this number is 40.
     * For Plus plans, this number is 80. You will regenerate requests allowed at a rate of two requests per second.
     * For more information about Shopify's API limit, see https://help.shopify.com/api/guides/api-call-limit
     */
    protected function setThrottleLimitsFromResponse()
    {
        $throttleHeader = $this->shopifyResponse->getHeader('X-Shopify-Shop-Api-Call-Limit');

        $throttle = $throttleHeader[0];

        list($requestsMade, $requestsRemaining) = explode('/', $throttle);

        $this->requestsMax = (int)$requestsRemaining;

        $this->requestsMade = (int)$requestsMade;
    }

    /**
     * Decode the JSON from the response body
     */
    protected function setResponseDataFromResponse()
    {
        $rawData = (string)$this->shopifyResponse->getBody();

        $decoded = json_decode($rawData, true);

        $this->responseData = $decoded;
    }

}
