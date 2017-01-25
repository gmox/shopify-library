<?php

namespace Shopify\Auth\Strategy;

abstract class Strategy
{
    protected $credentials;

    public function __construct(array $credentials = [])
    {
        $this->setCredentials($credentials);
    }

    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }
}
