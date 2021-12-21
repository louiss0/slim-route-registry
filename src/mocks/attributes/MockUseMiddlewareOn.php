<?php



namespace Louiss0\SlimRouteRegistry\Mocks\Attributes;

use Attribute;
use Louiss0\SlimRouteRegistry\Contracts\UseMiddlewareOnMethodsContract;

final class MockUseMiddleWareOn  implements UseMiddlewareOnMethodsContract
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
