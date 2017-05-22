<?php

namespace Tests\Auth\Strategy;

use Tests\TestCase;
use Shopify\Auth\Strategy\Strategy;

class StrategyTest extends TestCase
{
    /**
     * @group auth-tests
     * @group auth-strategy-tests
     *
     * @test
     */
    public function it_should_set_and_retrieve_credentials()
    {
        $strategy = \Mockery::mock(Strategy::class)->shouldDeferMissing();

        $strategy->setCredentials([
            'api_key'      => 'key',
            'api_password' => 'password',
        ]);

        $this->assertEquals([
            'api_key'      => 'key',
            'api_password' => 'password',
        ], $strategy->getCredentials());
    }
}
