<?php

namespace Shopify\Auth\Strategy;

use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;

class HttpBasic extends Strategy
{
    /**
     * Decorates a request to provide HTTP Basic authentication. HTTP Basic works via a header named 'Authorization'
     * that has 1) an indicator that it is a basic authorization; and 2) a base 64 encoded value of the username-password.
     * For Shopify, the username is the generated API Key and the password is the API Password.
     *
     * @inheritdoc
     */
    public function decorate(Request &$request)
    {
        // get the credentials we'll be using
        $credentials = $this->getCredentials();

        $apiKey      = $credentials['api_key'];
        $apiPassword = $credentials['api_password'];

        // create the basic auth value
        $basicAuth = 'Basic ' . base64_encode("$apiKey:$apiPassword");

        // modify the header
        $request = Psr7\modify_request($request, [
              'set_headers' => [
                  'Authorization' => $basicAuth
              ]
        ]);
    }
}
