<?php

namespace Louiss0\SlimRouteRegistry\Mocks\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Test2Middleware implements MiddlewareInterface
{



    function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {


        return  $handler->handle($request);
    }
}
