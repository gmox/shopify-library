<?php

namespace Shopify\Auth\Strategy;

use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;

class OAuth2 extends Strategy
{
    /**
     * Decorates a request to provide OAuth2 authentication. OAuth2 requires a token that will be set in the header
     * 'X-Shopify-Access-Token'. For more about generating an OAuth2 token, see https://help.shopify.com/api/guides/authentication/oauth
     *
     * @param Request  $request  The request that we are decorating
     */
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
