<?php

namespace Louiss0\SlimRouteRegistry\Traits;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

trait RouteRegistration

{


    private static  function registerRouteMethods(
        array $route_group_objects,
        RouteCollectorProxy $group
    ) {

        # code...
        array_walk($route_group_objects, function ($route_group_object) use ($group) {

            [
                "class_name" => $class_name,
                "method_name" => $method_name,
                "route_name" => $route_name,
                "callback_name" => $callback_name,
                "middleware" => $middleware,
                "path" => $path,

            ] = $route_group_object;

            $current_route = $group
                ->$method_name($path, [$class_name, $callback_name])
                ->setName($route_name);

            array_walk(
                callback: function (string $middleware) use ($current_route) {

                    $container = self::$app->getContainer();



                    if ($container->has($middleware)) {
                        # code...
                        return $current_route
                            ->addMiddleware($container->get($middleware));
                    }

                    $current_route->addMiddleware(new $middleware);
                },
                array: $middleware
            );
        });
    }
}