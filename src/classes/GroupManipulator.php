<?php

namespace Louiss0\SlimRouteRegistry\Classes;

use MiddlewareManipulator;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteGroupInterface;


class GroupManipulator
{



    private RouteCollectorProxyInterface $inner_group;

    private RouteGroupInterface $outer_group;



    /**
     * Get the value of inner_group
     */
    public function getInner_group()
    {
        return $this->inner_group;
    }


    public function setInner_group($inner_group): self
    {


        $this->inner_group = $inner_group;

        return $this;
    }


    public function getOuter_group()
    {
        return $this->outer_group;
    }



    public function setOuter_group($outer_group): self
    {

        $this->$outer_group = $outer_group;

        return $this;
    }

    public function registerMiddleware(string | object ...$middleware)
    {
        # code...


        return (new MiddlewareManipulator(
            outer_group: $this->getOuter_group()

        ))->registerMiddleware(...$middleware);
    }





    public  function get(string $pattern, callable | array $callable)
    {
        # code...

        return $this->getInner_group()->get($pattern, $callable);
    }


    public  function post(string $pattern, callable | array $callable)
    {
        # code...

        return $this->getInner_group()->post($pattern, $callable);
    }

    public function patch(string $pattern, callable | array $callable)
    {
        # code...

        return $this->getInner_group()->patch($pattern, $callable);
    }

    public function delete(string $pattern, callable | array $callable)
    {
        # code...

        return $this->getInner_group()->delete($pattern, $callable);
    }

    public function put(string $pattern, callable | array $callable)
    {
        # code...

        return $this->getInner_group()->put($pattern, $callable);
    }

    public function any(string $pattern, callable | array $callable)
    {
        # code...

        return $this->getInner_group()->any($pattern, $callable);
    }



    public function options(string $pattern, callable | array $callable)

    {
        # code...

        return $this->getInner_group()->options($pattern, $callable);
    }



    function registerRouteMethods(array $route_group_objects, ContainerInterface $container)
    {

        # code...
        array_walk(
            callback: function ($route_group_object,) use ($container) {

                [
                    "class_name" => $class_name,
                    "method_name" => $method_name,
                    "route_name" => $route_name,
                    "callback_name" => $callback_name,
                    "middleware" => $middleware,
                    "path" => $path,

                ] = $route_group_object;


                $current_route = $this->getInner_group()
                    ->$method_name($path, [$class_name, $callback_name])
                    ->setName($route_name);

                array_walk(
                    callback: function (string $middleware) use ($current_route, $container) {


                        if ($container->has($middleware)) {
                            # code...
                            return $current_route
                                ->addMiddleware($container->get($middleware));
                        }

                        $current_route->addMiddleware(new $middleware);
                    },
                    array: $middleware
                );
            },
            array: $route_group_objects


        );
    }
}
