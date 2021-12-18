<?php


namespace Louiss0\SlimRouteRegistry\Traits;

use Closure;
use Louiss0\SlimRouteRegistry\Contracts\{UseMiddlewareContract, UseMiddlewareOnMethodsContract};

trait AlterRouteGroupMap
{


    private array $route_group_objects;



    public function getRoute_group_objects()
    {
        return $this->route_group_objects;
    }


    public function createNewRouteGroupObjectsFromUseMiddlewareInstance(UseMiddlewareContract $use_middleware_instance): self
    {
        $middleware = $use_middleware_instance->getMiddleware();



        $this->route_group_objects = array_map(
            callback: function (array $route_group_object) use ($middleware) {
                [
                    "middleware" => $old_middleware
                ] = $route_group_object;
                return array_merge(
                    $route_group_object,
                    ["middleware" => [...$old_middleware, $middleware]]
                );
            },
            array: $this->route_group_objects
        );


        return $this;
    }


    private function createNewRouteGroupObjectsFromUseMiddlewareMethodsNamesAndMiddleware(
        Closure $closure,
        array $use_middleware_instances
    ): array {

        return array_map(
            callback: function (array $route_group_object) use ($closure, $use_middleware_instances) {
                # code...
                return array_reduce(
                    callback: function (array $route_group_object, UseMiddlewareOnMethodsContract $use_middleware_instance) use ($closure) {
                        [$method_names, $middleware] = [
                            $use_middleware_instance->getMethodNames(),
                            $use_middleware_instance->getMiddleware()
                        ];



                        return $closure(
                            $route_group_object,
                            $method_names,
                            $middleware
                        );
                    },
                    array: $use_middleware_instances,
                    initial: $route_group_object
                );
            },
            array: $this->route_group_objects
        );
    }


    public function replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareOnAttributes(UseMiddlewareContract ...$use_middleware_instances): self
    {
        # code...


        $this->route_group_objects = $this->createNewRouteGroupObjectsFromUseMiddlewareMethodsNamesAndMiddleware(
            function (array $route_group_object, array $method_names, array $middleware) {

                [
                    "callback_name" => $callback_name,
                    "middleware" => $old_middleware
                ] = $route_group_object;

                $callback_name_in_method_names =
                    in_array($callback_name, $method_names, true);

                return $callback_name_in_method_names

                    ? array_merge(
                        $route_group_object,
                        ["middleware" => [...$old_middleware, ...$middleware]]
                    )
                    : $route_group_object;
            },
            $use_middleware_instances
        );

        return $this;
    }


    public function replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareExceptForAttributes(
        UseMiddlewareContract ...$use_middleware_instances
    ): self {

        # code...


        $this->route_group_objects =
            $this->createNewRouteGroupObjectsFromUseMiddlewareMethodsNamesAndMiddleware(
                closure: function (array $route_group_object, array $method_names, array $middleware) {

                    [
                        "callback_name" => $callback_name,
                        "middleware" => $old_middleware
                    ] = $route_group_object;

                    $callback_name_not_in_method_names =
                        !in_array($callback_name, $method_names, true);

                    return $callback_name_not_in_method_names
                        ? array_merge(
                            $route_group_object,
                            ["middleware" => [...$old_middleware, ...$middleware]]
                        )
                        : $route_group_object;
                },
                use_middleware_instances: $use_middleware_instances
            );
        return $this;
    }
}
