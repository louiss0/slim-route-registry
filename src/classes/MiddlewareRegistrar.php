<?php

namespace Louiss0\SlimRouteRegistry\Classes;

use Louiss0\SlimRouteRegistry\Contracts\MiddlewareRegistrarContract;
use Slim\Interfaces\RouteGroupInterface;

class MiddlewareRegistrar implements MiddlewareRegistrarContract
{



    public function __construct(
        private RouteGroupInterface $outer_group
    ) {
    }

    public function registerMiddleware(string| object ...$middleware): self
    {
        # code...


        array_walk(
            callback: fn (string| object $middleware) =>
            $this->outer_group->addMiddleware($middleware),
            array: $middleware
        );

        return $this;
    }
}
