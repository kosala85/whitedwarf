<?php

namespace Api\Middleware;

class TransformerMiddleware
{
    /**
     * Transformer middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        // inbound manipulations


        // pass to next level
        $response = $next($request, $response);

        // outbound manipulations
        $response = $response->withHeader('Content-type', 'application/json');

        return $response;
    }
}