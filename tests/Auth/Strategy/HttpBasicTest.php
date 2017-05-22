<?php

namespace Tests\Auth\Strategy;

use Tests\TestCase;
use GuzzleHttp\Psr7\Request;
use Shopify\Auth\Strategy\HttpBasic;

class HttpBasicTest extends TestCase
{
    /**
     * @group auth-tests
     * @group auth-strategy-tests
     * @group auth-http-basic-tests
     *
     * @test
     */
    public function it_should_decorate_request_with_http_basic_header()
    {
        $credentials = [
            'api_key'      => 'key',
            'api_password' => 'password',
        ];

        $request = new Request('GET', '/');

        $strategy = new HttpBasic();

        $strategy->setCredentials($credentials);

        $strategy->decorate($request);

        $this->assertTrue($request->hasHeader('Authorization'));

        $expected = ['Basic ' . base64_encode($credentials['api_key'] . ':' . $credentials['api_password'])];

        $this->assertEquals($expected, $request->getHeader('Authorization'));
    }
}
