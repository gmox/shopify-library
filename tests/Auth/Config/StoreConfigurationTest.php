<?php

namespace Tests\Auth\Config;

use Shopify\Auth\Config\StoreConfiguration;

class StoreConfigurationTest extends \TestCase
{
    /** @var StoreConfiguration::class */
    protected $config;

    public function setUp()
    {
        parent::setUp();

        $this->config = new StoreConfiguration([
            'store_name' => 'test-store'
        ]);
    }
    
    /**
     * @group auth-tests
     * @group auth-config-tests
     *
     * @test
     */
    public function it_should_fill_configuration_via_constructor()
    {
        $this->assertEquals([
            'store_name' => 'test-store'
        ], $this->config->getConfiguration());
    }

    /**
     * @group auth-tests
     * @group auth-config-tests
     *
     * @test
     */
    public function it_should_set_configuration_via_setter()
    {
        $this->config->store_name = 'new-store';

        $this->assertEquals([
            'store_name' => 'new-store'
        ], $this->config->getConfiguration());
    }

    /**
     * @group auth-tests
     * @group auth-config-tests
     *
     * @test
     */
    public function it_should_get_configuration_via_getter()
    {
        $this->assertEquals('test-store', $this->config->store_name);
    }
}
