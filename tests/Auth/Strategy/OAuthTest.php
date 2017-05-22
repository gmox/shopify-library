<?php

namespace Tests\Auth\Strategy;

use Tests\TestCase;
use GuzzleHttp\Psr7\Request;
use Shopify\Auth\Strategy\OAuth2;

class OAuthTest extends TestCase
{
    /**
     * @group auth-tests
     * @group auth-strategy-tests
     * @group auth-http-basic-tests
     *
     * @test
     */
    public function it_should_decorate_request_with_shopify_access_token_header()
    {
        $credentials = [
            'oauth_token' => 'token',
        ];

        $request = new Request('GET', '/');

        $strategy = new OAuth2();

        $strategy->setCredentials($credentials);

        $strategy->decorate($request);

        $this->assertTrue($request->hasHeader('X-Shopify-Access-Token'));

        $expected = [$credentials['oauth_token']];

        $this->assertEquals($expected, $request->getHeader('X-Shopify-Access-Token'));
    }
}
