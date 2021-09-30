<?php


namespace Louiss0\SlimRouteRegistry\Utils\Traits;

use Illuminate\Support\Collection;
use Slim\Interfaces\RouteInterface;
use Louiss0\SlimRouteRegistry\Attributes\UseMiddleWareExceptFor;
use Louiss0\SlimRouteRegistry\Attributes\UseMiddleWareOn;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Interfaces\RouteGroupInterface;

trait  MiddlewareRegistration
{
    private static function registerMiddlewareIfUseMiddlewareOn(
        Collection $attribute_instances,
        RouteInterface $route,
        string $method_name
    ) {
        # code...



        $use_middleware_on_attributes_array = $attribute_instances->filter(
            fn (string | object $attribute_instance) =>
            is_a($attribute_instance, UseMiddleWareOn::class,)
        );



        $use_middleware_on_attributes_array_is_not_empty =

            $use_middleware_on_attributes_array->isNotEmpty();

        if ($use_middleware_on_attributes_array_is_not_empty) {
            # code...

            $use_middleware_on_attributes_array->each(
                function ($attribute) use ($route, $method_name) {
                    # code...

                    [$middleware, $method_names] = [
                        $attribute->getMiddleware(),
                        $attribute->getMethodNames()

                    ];

                    if (in_array($method_name, $method_names)) {

                        # code...

                        array_walk(
                            $middleware,
                            function (string| object $value) use ($route) {

                                $route->addMiddleware(new $value);
                            }
                        );
                    }
                }
            );
        }
    }




    private static function registerMiddlewareIfUseMiddleWareExceptFor(
        Collection $attribute_instances,
        RouteInterface $route,
        string $method_name
    ) {
        # code...



        $use_middleware_except_for_attributes_array = $attribute_instances->filter(
            fn (string| object $attribute_instance) =>
            is_a($attribute_instance, UseMiddleWareExceptFor::class)
        );


        $use_middleware_except_for_attributes_array_is_not_empty = $use_middleware_except_for_attributes_array->isNotEmpty();

        if ($use_middleware_except_for_attributes_array_is_not_empty) {
            # code...


            $use_middleware_except_for_attributes_array->each(
                function ($attribute) use ($route, $method_name) {
                    # code...

                    [$middleware, $method_names] = [
                        $attribute->getMiddleware(),
                        $attribute->getMethodNames(),
                    ];

                    if (!in_array($method_name, $method_names)) {

                        # code...

                        array_walk(
                            $middleware,
                            function (string| object $value) use ($route) {


                                $route->addMiddleware(new $value);
                            }
                        );
                    }
                }
            );
        }
    }



    private static function registerMiddlewareThatUsesTheMiddlwareInterface(
        Collection $attribute_instances,
        RouteGroupInterface | RouteInterface $routeCreator,
    ): void {
        # code...

        $attribute_instances
            ->filter(fn (object $object) => is_a($object, MiddlewareInterface::class))
            ->each(fn (MiddlewareInterface $middleware) => $routeCreator->addMiddleware($middleware));
    }
}
