<?php

namespace Shopify\Auth\Config;

class StoreConfiguration
{
    /** @var array */
    protected $configuration;

    /**
     * Create a StoreConfiguration object. This holds relevant data about the store necessary for the request (e.g, name)
     *
     * @param array  $configuration  An array of data that will be used as the configuration
     */
    public function __construct(array $configuration = [])
    {
        $this->setConfiguration($configuration);
    }

    /**
     * Sets the store configuration to the value in the array
     *
     * @param array  $configuration  An array of data that will be used as the configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Returns the store configuration
     *
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * Gets a value from the configuration object-style ($obj->key)
     *
     * @param string|integer  $key  The name of the configuration value we want
     * @return mixed
     */
    public function __get($key)
    {
        // if the key doesn't exist, return null
        if (!isset($this->configuration[$key])) {
            return null;
        }

        return $this->configuration[$key];
    }

    /**
     * Sets a value on the configuration object-style ($obj->key = value)
     *
     * @param string|integer  $key   The name of the configuration value we want to set
     * @param mixed           $value Value we're assigning to the key
     */
    public function __set($key, $value)
    {
        $this->configuration[$key] = $value;
    }
}
