<?php


namespace Louiss0\SlimRouteRegistry\Utils\Classes;

use Louiss0\SlimRouteRegistry\Attributes\{
    UseMiddleWareExceptFor,
    UseMiddleWareOn
};
use Louiss0\SlimRouteRegistry\Contracts\Internal\UseMiddlewareObjectCreatorContract;
use Louiss0\SlimRouteRegistry\Utils\Traits\AlterRouteGroupMap;

final class MapCreator
{


    use  AlterRouteGroupMap;

    public function __construct(
        private UseMiddlewareObjectCreatorContract $useMiddlewareObjectCreator
    ) {

        $this->route_group_objects = collect([]);
        $this->middleware_on_collection = $this->useMiddlewareObjectCreator->getMiddleware_on_collection();
        $this->middleware_except_collection = $this->useMiddlewareObjectCreator->getMiddleware_except_collection();
    }


    function generateKeysAndValuesForMiddlewareOnCollection(
        UseMiddleWareOn ...$use_on_middleware_instances
    ): void {


        $this->useMiddlewareObjectCreator
            ->generateKeysAndValuesForMiddlewareOnCollection(...$use_on_middleware_instances);
    }

    public function generateKeysAndValuesForMiddlewareExceptCollection(
        UseMiddleWareExceptFor ...$use_except_for_middleware_instances
    ): void {

        $this->useMiddlewareObjectCreator
            ->generateKeysAndValuesForMiddlewareExceptCollection(...$use_except_for_middleware_instances);
    }
}
