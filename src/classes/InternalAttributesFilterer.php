<?php


namespace Louiss0\SlimRouteRegistry\Classes;

use Louiss0\SlimRouteRegistry\Attributes\{
    RouteMethod,
    UseMiddleWareExceptFor,
    UseMiddleWareOn
};
use Louiss0\SlimRouteRegistry\Contracts\UseMiddlewareContract;

use function Louiss0\SlimRouteRegistry\Utils\Helpers\{array_first};

final class InternalAttributesFilterer
{



    public function amassUseMiddlewareAttributes(object ...$classes)
    {
        return array_filter(
            callback: fn (object $class) =>
            is_a($class, UseMiddlewareContract::class),
            array: $classes
        );
    }

    public function findRouteAttributeInstance(object ...$classes)
    {
        return array_first(
            callback: fn (object $object) =>
            is_a($object, RouteMethod::class),
            array: $classes
        );
    }


    public function amassUseMiddlewareOnAttributes(object ...$classes)
    {

        return array_filter(
            callback: fn (object $class) =>
            is_a($class, UseMiddleWareOn::class),
            array: $classes
        );
    }

    public function amassUseMiddlewareExceptForAttributes(object ...$classes)
    {

        return array_filter(
            callback: fn (object $class) =>
            is_a($class, UseMiddleWareExceptFor::class),
            array: $classes
        );
    }
}
