<?php

namespace Shopify\Auth\Strategy;

use Shopify\Contracts\Clients\RequestDecorator;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7;


class OAuth2 extends Strategy implements RequestDecorator
{
    public function decorate(Request &$request)
    {
        $credentials = $this->getCredentials();

        $oauthToken = $credentials['oauth_token'];

        $request = $request->withHeader('X-Shopify-Access-Token', $oauthToken);

        $request = Psr7\modify_request($request, [
            'set_headers' => [
                'X-Shopify-Access-Token' => $oauthToken
            ]
        ]);
    }
}
