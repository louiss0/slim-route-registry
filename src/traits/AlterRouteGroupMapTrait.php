<?php


namespace Louiss0\SlimRouteRegistry\Traits;

use Illuminate\Support\Collection;
use ReflectionAttribute;
use ReflectionMethod;
use Louiss0\SlimRouteRegistry\Attributes\RouteMethod;

trait AlterRouteGroupMapTrait
{




    private static function alterRouteMapIfRouteMethodAttributeExists(
        Collection $methodAttributes,
        Collection $route_group_map,
        ReflectionMethod $method
    ) {
        # code...

        $route_group_method_attribute =
            $methodAttributes
            ->first(fn (ReflectionAttribute $reflectionAttribute) => $reflectionAttribute->newInstance() instanceof RouteMethod)
            ?->newInstance();




        if ($route_group_method_attribute) {
            # code...


            $route_group_map->push(
                [
                    "method_name" => $route_group_method_attribute->getMethod(),
                    "name" => $route_group_method_attribute->getName(),
                    "path" => $route_group_method_attribute->getPath(),
                    "callback" => $method->getName(),
                ]
            );
        }
    }
}
