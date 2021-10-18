<?php


namespace Louiss0\SlimRouteRegistry\Classes;


use Louiss0\SlimRouteRegistry\Enums\AutomaticRegistrationMethodNames;
use Louiss0\SlimRouteRegistry\Enums\RouteMethodNames;

use Louiss0\SlimRouteRegistry\Traits\AlterRouteGroupMap;

final class RouteObjectCollector
{


    use AlterRouteGroupMap;

    public function __construct()
    {

        $this->route_group_objects = [];
    }


    public function addRouteNecessitiesToRouteObject(
        string $class_name,
        string $method_name,
        string $route_name,
        string $callback_name,
        array $middleware,
        string $path = "",
    ): void {
        # code...



        $this->route_group_objects =  [
            ...$this->getRoute_group_objects(),
            compact(
                "class_name",
                "method_name",
                "route_name",
                "callback_name",
                "middleware",
                "path",
            )
        ];
    }


    public function addRouteNecessitiesToRouteObjectWithIdFilledIn(
        string $class_name,
        string $method_name,
        string $route_name,
        string $callback_name,
        array $middleware,

    ): void {
        # code...


        $this->addRouteNecessitiesToRouteObject(
            path: "/{id:\d+}",
            route_name: $route_name,
            method_name: $method_name,
            callback_name: $callback_name,
            class_name: $class_name,
            middleware: $middleware,
        );
    }


    public function addRouteRouteGroupObjectBasedOnMethodName(
        string $path,
        string $class_name,
        string $callback_name,
        array $middleware,
    ): void {
        # code...

        $path = str_replace("/", "", $path);

        $route_name = "{$path}.{$callback_name}";

        switch ($callback_name) {
            case AutomaticRegistrationMethodNames::GET_ANY:
                $this->addRouteNecessitiesToRouteObject(
                    $class_name,
                    RouteMethodNames::GET,
                    $route_name,
                    $callback_name,
                    $middleware
                );
                break;
            case AutomaticRegistrationMethodNames::GET_ONE:
                $this->addRouteNecessitiesToRouteObjectWithIdFilledIn(
                    $class_name,
                    RouteMethodNames::GET,
                    $route_name,
                    $callback_name,
                    $middleware
                );
                break;
            case AutomaticRegistrationMethodNames::UPDATE_OR_CREATE_ONE:
                $this->addRouteNecessitiesToRouteObject(
                    $class_name,
                    RouteMethodNames::PUT,
                    $route_name,
                    $callback_name,
                    $middleware
                );
                break;
            case AutomaticRegistrationMethodNames::UPDATE_ONE:
                $this->addRouteNecessitiesToRouteObjectWithIdFilledIn(
                    $class_name,
                    RouteMethodNames::PATCH,
                    $route_name,
                    $callback_name,
                    $middleware
                );
                break;
            case AutomaticRegistrationMethodNames::DELETE_ONE:
                $this->addRouteNecessitiesToRouteObjectWithIdFilledIn(
                    $class_name,
                    RouteMethodNames::DELETE,
                    $route_name,
                    $callback_name,
                    $middleware
                );
                break;
            case AutomaticRegistrationMethodNames::CREATE_ONE:
                $this->addRouteNecessitiesToRouteObject(
                    $class_name,
                    RouteMethodNames::POST,
                    $route_name,
                    $callback_name,
                    $middleware
                );
                break;
        }
    }

    public function flushRouteObjects()
    {
        # code...
        $this->route_group_objects = [];
    }
}
