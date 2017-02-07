<?php

namespace Api\Core\Middleware;

use Api\Core\Exceptions\Types\MiddlewareException;

class AuthMiddleware
{
    /**
     * Authentication middleware invokable class.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface $response PSR7 response
     * @param  callable $next Next middleware
     * @return \Psr\Http\Message\ResponseInterface
     * @throws MiddlewareException
     */
    public function __invoke($request, $response, $next)
    {
        // inbound manipulations
        $arrQueryParams = $request->getQueryParams();
        $strToken = isset($arrQueryParams['token']) ? $arrQueryParams['token'] : '';

        $authenticator = $GLOBALS['auth'];
        $authenticator->authenticate($strToken);

        // pass to next level
        $response = $next($request, $response);

        // outbound manipulations
        
        return $response;
    }
}