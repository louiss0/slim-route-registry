<?php


namespace Louiss0\SlimRouteRegistry\Utils\Traits;

use Illuminate\Support\Collection;
use Louiss0\SlimRouteRegistry\Enums\AutomaticRegistrationMethodNames;
use Louiss0\SlimRouteRegistry\Enums\RouteMethodNames;

trait AlterRouteGroupMap
{


    private Collection $route_group_objects;


    public function getRoute_group_objects()
    {
        return $this->route_group_objects->all();
    }

    public function addRouteNecessitiesToRouteObject(
        string $class_name,
        string $method_name,
        string $route_name,
        string $callback_name,
        Collection  $middleware,
        string $path = "",
    ): void {
        # code...


        $this->route_group_objects->push(
            compact(
                "class_name",
                "method_name",
                "route_name",
                "callback_name",
                "middleware",
                "path",
            )
        );
    }


    public function addRouteNecessitiesToRouteObjectWithIdFilledIn(
        string $class_name,
        string $method_name,
        string $route_name,
        string $callback_name,
        Collection $middleware,

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


    public function addMiddlewareToRouteMapBasedOnUseMiddlewareOnCollection(): void
    {

        # code...

        $this->middleware_on_collection->each(
            function (array $middleware_strings, string $method_name) {

                # code...
                $this->route_group_objects->transform(
                    function (array $route_group_object) use ($middleware_strings, $method_name) {

                        # code...
                        [
                            "callback_name" => $callback_name,
                            "middleware" =>  $middleware
                        ]   = $route_group_object;

                        $check_if_method_name_equals_callback_name = $method_name === $callback_name;

                        if ($check_if_method_name_equals_callback_name) {
                            # code...


                            return array_merge(
                                $route_group_object,
                                ["middleware" => $middleware->merge($middleware_strings)]
                            );
                        }

                        return $route_group_object;
                    }
                );
            }
        );
    }


    public function addMiddlewareToRouteObjectBasedOnUseMiddlewareExceptForCollection(): void
    {

        # code...
        $this->middleware_except_collection->each(
            function (array $middleware_strings, string $method_name) {
                # code...

                $this->route_group_objects->transform(
                    function (array $route_group_object) use ($middleware_strings, $method_name) {
                        # code...

                        [
                            "callback_name" => $callback_name,
                            "middleware" =>  $middleware
                        ]   = $route_group_object;


                        $check_if_method_name_does_not_equal_callback_name = $method_name !== $callback_name;


                        if ($check_if_method_name_does_not_equal_callback_name) {
                            # code...

                            return array_merge(
                                $route_group_object,
                                ["middleware" => $middleware->merge($middleware_strings)]
                            );
                        }

                        return $route_group_object;
                    }
                );
            }
        );
    }


    public function addRouteRouteGroupObjectBasedOnMethodName(
        string $path,
        string $class_name,
        string $callback_name,
        Collection $middleware,
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
}
