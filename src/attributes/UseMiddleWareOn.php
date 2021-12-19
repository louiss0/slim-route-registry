<?php



namespace Louiss0\SlimRouteRegistry\Attributes;

use Attribute;
use Louiss0\SlimRouteRegistry\Contracts\UseMiddlewareContract;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class UseMiddleWareOn  implements UseMiddlewareContract
{




    public function __construct(
        private array $method_names,
        private array $middleware
    ) {
    }




    /**
     * Get the value of method_names
     */
    public function getMethodNames(): array
    {
        return $this->method_names;
    }

    function getMiddleware(): array
    {
        return $this->middleware;
    }
}
