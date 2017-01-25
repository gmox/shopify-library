<?php

namespace Tests\Auth\Config;

use Shopify\Auth\Config\StoreConfiguration;

class StoreConfigurationTest extends \TestCase
{
    /**
     * @group auth-tests
     * @group auth-config-tests
     *
     * @test
     */
    public function it_should_fill_configuration_via_constructor()
    {
        $config = new StoreConfiguration([
            'store_name' => 'test-store'
        ]);

        $this->assertEquals([
            'store_name' => 'test-store'
        ], $config->getConfiguration());
    }

    /**
     * @group auth-tests
     * @group auth-config-tests
     *
     * @test
     */
    public function it_should_set_configuration_via_setter()
    {
        $config = new StoreConfiguration([
            'store_name' => 'test-store'
        ]);

        $config->store_name = 'new-store';

        $this->assertEquals([
            'store_name' => 'new-store'
        ], $config->getConfiguration());
    }

    /**
     * @group auth-tests
     * @group auth-config-tests
     *
     * @test
     */
    public function it_should_get_configuration_via_getter()
    {
        $config = new StoreConfiguration([
            'store_name' => 'test-store'
        ]);

        $this->assertEquals('test-store', $config->store_name);
    }
}
