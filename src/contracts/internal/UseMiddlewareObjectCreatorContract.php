<?php


namespace Louiss0\SlimRouteRegistry\Contracts\Internal;

use Illuminate\Support\Collection;
use Louiss0\SlimRouteRegistry\Attributes\{
    UseMiddleWareOn,
    UseMiddleWareExceptFor
};

interface UseMiddlewareObjectCreatorContract
{

    function getMiddleware_on_collection(): Collection;


    function getMiddleware_except_collection(): Collection;

    function generateKeysAndValuesForAMiddlewareCollection(
        array $use_middleware_instances,
        Collection $middleware_map
    ): void;


    function generateKeysAndValuesForMiddlewareOnCollection(
        UseMiddleWareOn ...$use_middleware_instances
    ): void;


    function generateKeysAndValuesForMiddlewareExceptCollection(
        UseMiddleWareExceptFor ...$use_except_for_middleware_instances
    ): void;
}
