<?php

namespace Shopify\Contracts\Clients;

use GuzzleHttp\Psr7\Request;

interface RequestDecorator
{
    /**
     * Decorates a request to provide the proper authentication to a request.
     *
     * @param Request  $request  The request that we are decorating
     * @return void
     */
    public function decorate(Request &$request);
}
