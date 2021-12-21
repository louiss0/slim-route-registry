<?php



namespace Louiss0\SlimRouteRegistry\Attributes;

use Attribute;
use Louiss0\SlimRouteRegistry\Contracts\UseMiddlewareOnMethodsContract;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class UseMiddleWareOn  implements UseMiddlewareOnMethodsContract
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
        return array_map(
            fn (string $middleware_ref) => new $middleware_ref,
            $this->middleware
        );
    }
}
