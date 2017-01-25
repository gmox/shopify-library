<?php

namespace Shopify\Clients;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response
{
    /** @var GuzzleResponse::class */
    protected $shopifyResponse;

    /** @var integer */
    protected $requestsMax;

    /** @var integer */
    protected $requestsMade;

    /** @var array */
    protected $responseData;

    public function __construct($shopifyResponse)
    {
        $this->setShopifyResponse($shopifyResponse);

        $this->setThrottleLimitsFromResponse();

        $this->setResponseDataFromResponse();
    }

    public function setShopifyResponse($shopifyResponse)
    {
        $this->shopifyResponse = $shopifyResponse;
    }

    public function getShopifyResponse()
    {
        return $this->shopifyResponse;
    }

    public function getRequestsRemaining()
    {
        return $this->getRequestsMax() - $this->getRequestsMade();
    }

    public function getRequestsMade()
    {
        return $this->requestsMade;
    }

    public function getRequestsMax()
    {
        return $this->requestsMax;
    }

    public function getResponseCode()
    {
        return $this->shopifyResponse->getStatusCode();
    }

    public function getResponseData()
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

    protected function setResponseDataFromResponse()
    {
        $rawData = (string)$this->shopifyResponse->getBody();

        $decoded = json_decode($rawData, true);

        $this->responseData = $decoded;
    }

}
