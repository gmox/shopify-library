<?php

namespace Shopify\Auth\Strategy;

use Shopify\Contracts\Clients\RequestDecorator;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7;

class HttpBasic extends Strategy implements RequestDecorator
{
    public function decorate(Request &$request)
    {
        $credentials = $this->getCredentials();

        $apiKey = $credentials['api_key'];
        $apiPassword = $credentials['api_password'];

        $basicAuth = 'Basic ' . base64_encode("$apiKey:$apiPassword");

        $request = Psr7\modify_request($request, [
              'set_headers' => [
                  'Authorization' => $basicAuth
              ]
        ]);
    }
}
