<?php

namespace Shopify\Contracts\Clients;

use Shopify\Clients\Response;

interface HttpClient
{
    /**
     * Executes a request to an endpoint using the supplied parameters.
     *
     * @param string  $httpMethod       The HTTP Method of the request
     * @param string  $httpEndpoint     The endpoint of the request
     * @param array   $queryParameters  The query parameters to be used in the request
     * @param array   $data             The data that will be sent in the request body
     * @return Response
     */
    public function execute($httpMethod, $httpEndpoint, array $queryParameters = [], array $data = []) : Response;
}
