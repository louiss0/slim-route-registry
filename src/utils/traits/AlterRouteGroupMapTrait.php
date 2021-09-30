<?php


namespace Louiss0\SlimRouteRegistry\Utils\Traits;

use Illuminate\Support\Collection;
use Louiss0\SlimRouteRegistry\Attributes\RouteMethod;
use Psr\Http\Server\MiddlewareInterface;

trait AlterRouteGroupMapTrait
{


    protected  static Collection $route_group_map;

    private static function alterRouteMap(
        string $class_name,
        string $method_name,
        string $name,
        string $callback_name,
        Collection  $middleware,
        string $path = "",
    ): void {



        self::$route_group_map->push(
            compact(
                "class_name",
                "method_name",
                "name",
                "path",
                "callback_name",
                "middleware",
            )
        );
    }


    private static function alterRouteMapIfRouteMethodAttributeExists(
        Collection $method_attribute_instances,
        string $class_name,
        string $method_name
    ) {
        # code...

        $route_group_method_attribute =
            $method_attribute_instances
            ->first(fn (object $instance) => is_a($instance, RouteMethod::class));






        if ($route_group_method_attribute) {
            # code...


            self::alterRouteMap(
                class_name: $class_name,
                method_name: $route_group_method_attribute->getMethod(),
                name: $route_group_method_attribute->getName(),
                path: $route_group_method_attribute->getPath(),
                callback_name: $method_name,
                middleware: $method_attribute_instances
                    ->filter(
                        fn (object $instance) =>
                        is_a($instance, MiddlewareInterface::class)
                    )
            );
        }
    }
}
