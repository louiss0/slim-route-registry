<?php


namespace Louiss0\SlimRouteRegistry\Utils\Classes;

use Illuminate\Support\Collection;
use Louiss0\SlimRouteRegistry\Attributes\{
    UseMiddleWareExceptFor,
    UseMiddleWareOn
};
use Louiss0\SlimRouteRegistry\Contracts\Internal\UseMiddlewareObjectCreatorContract;
use Louiss0\SlimRouteRegistry\Contracts\UseMiddlewareContract;

class MiddlewareOrganizer implements UseMiddlewareObjectCreatorContract
{

    private Collection $middleware_on_collection;

    private Collection $middleware_except_collection;


    public function __construct()
    {
        $this->middleware_on_collection = collect([]);

        $this->middleware_except_collection  = collect([]);
    }

    /**
     * Get the value of middleware_on_collection
     */
    public function getMiddleware_on_collection(): Collection
    {
        return $this->middleware_on_collection;
    }

    /**
     * Get the value of middleware_except_collection
     */
    public function getMiddleware_except_collection(): Collection
    {
        return $this->middleware_except_collection;
    }

    function generateKeysAndValuesForAMiddlewareCollection(
        array $use_middleware_instances,
        Collection $middleware_map
    ): void {
        # code...


        collect($use_middleware_instances)->each(
            function (UseMiddlewareContract $use_middleware_instance) use ($middleware_map) {

                [$method_names, $middleware] = [
                    $use_middleware_instance->getMethodNames(),
                    array_map(
                        fn (string $middleware) => new $middleware,
                        $use_middleware_instance->getMiddleware()
                    ),
                ];

                collect($method_names)->each(function (string $method_name) use ($middleware, $middleware_map) {

                    if ($middleware_map->has($method_name)) {
                        # code...

                        $old_middleware = $middleware_map->get($method_name, []);

                        return $middleware_map->put(
                            $method_name,
                            array_unique(
                                array: array_merge(
                                    $old_middleware,
                                    $middleware
                                ),
                                flags: SORT_REGULAR
                            )
                        );
                    }

                    $middleware_map->put($method_name, $middleware);
                });
            },
        );
    }

    public function generateKeysAndValuesForMiddlewareOnCollection(
        UseMiddleWareOn ...$use_middleware_instances
    ): void {

        $this->generateKeysAndValuesForAMiddlewarecollection(
            use_middleware_instances: $use_middleware_instances,
            middleware_map: $this->middleware_on_collection
        );
    }

    public function generateKeysAndValuesForMiddlewareExceptcollection(
        UseMiddleWareExceptFor ...$use_except_for_middleware_instances
    ): void {
        # code...
        $this->generateKeysAndValuesForAMiddlewarecollection(
            use_middleware_instances: $use_except_for_middleware_instances,
            middleware_map: $this->middleware_except_collection
        );
    }
}
