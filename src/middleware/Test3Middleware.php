<?php



namespace Louiss0\SlimRouteRegistry\Middleware;

use Attribute;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class Test3Middleware  implements MiddlewareInterface
{

    function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        return $handler->handle($request);
    }
}
