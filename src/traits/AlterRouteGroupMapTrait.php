<?php


namespace Louiss0\SlimRouteRegistry\Traits;

use Illuminate\Support\Collection;
use ReflectionAttribute;
use ReflectionMethod;
use Louiss0\SlimRouteRegistry\Attributes\RouteMethod;
use PhpParser\Node\Expr\Instanceof_;
use Psr\Http\Server\MiddlewareInterface;

trait AlterRouteGroupMapTrait
{




    private static function alterRouteMapIfRouteMethodAttributeExists(
        Collection $method_attribute_instances,
        Collection $route_group_map,
        string $method_name
    ) {
        # code...

        $route_group_method_attribute =
            $method_attribute_instances
            ->first(fn (object $instance) => $instance instanceof RouteMethod)
            ?->newInstance();




        if ($route_group_method_attribute) {
            # code...


            $route_group_map->push(
                [
                    "method_name" => $route_group_method_attribute->getMethod(),
                    "name" => $route_group_method_attribute->getName(),
                    "path" => $route_group_method_attribute->getPath(),
                    "callback" => $method_name,
                    "middleware" => $method_attribute_instances
                        ->filter(
                            fn (object $instance) =>
                            $instance instanceof MiddlewareInterface

                        )
                ]
            );
        }
    }
}
