<?php

namespace Shopify\Auth\Strategy;

use Shopify\Contracts\Clients\RequestDecorator;

abstract class Strategy implements RequestDecorator
{
    /** @var array */
    protected $credentials;

    /**
     * Creates an instance of the strategy and set the credentials.
     *
     * @param array  $credentials  The credentials we're using to authenticate
     */
    public function __construct(array $credentials = [])
    {
        $this->setCredentials($credentials);
    }

    /**
     * Sets the credentials
     *
     * @param array  $credentials  The credentials we're using to authenticate
     */
    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Gets the credentials
     *
     * @return array
     */
    public function getCredentials(): array
    {
        return $this->credentials;
    }
}
