<?php

namespace Api\Middleware;

class JsonMiddleware
{
    /**
     * Json middleware invokable class.
     *
     *  Checks whether JSON data is sent to the API and make sure the header is set to 'applicatio/json' in the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface $response PSR7 response
     * @param  callable $next Next middleware
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function __invoke($request, $response, $next)
    {
        // inbound manipulations
        if($request->getHeader('Accept')[0] != 'application/json')
        {
            throw new \Exception("Api only accepts JSON data");
        }

        // pass to next level
        $response = $next($request, $response);

        // outbound manipulations
        $response = $response->withHeader('Content-type', 'application/json');

        return $response;
    }
}