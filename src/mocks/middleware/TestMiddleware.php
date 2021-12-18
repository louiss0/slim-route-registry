<?php

namespace Louiss0\SlimRouteRegistry\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TestMiddleware implements MiddlewareInterface
{



    function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {


        return  $handler->handle($request);
    }
}
