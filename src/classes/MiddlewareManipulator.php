<?php

namespace Louiss0\SlimRouteRegistry\Classes;

use Slim\Interfaces\RouteGroupInterface;

class MiddlewareManipulator
{



    public function __construct(
        private RouteGroupInterface $outer_group
    ) {
    }

    public function registerMiddleware(string| object ...$middleware)
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
