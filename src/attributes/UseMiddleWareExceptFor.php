<?php

namespace Louiss0\SlimRouteRegistry\Attributes;

use Attribute;
use Louiss0\SlimRouteRegistry\Contracts\UseMiddlewareOnMethodsContract;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class UseMiddleWareExceptFor implements UseMiddlewareOnMethodsContract
{



    public function __construct(
        private array $method_names,
        private array $middleware
    ) {
    }

    /**
     * Get the value of methods
     */
    public function getMethodNames(): array
    {
        return $this->method_names;
    }

    /**
     * Get the value of middleware
     */
    public function getMiddleware(): array
    {
        return
            array_map(
                fn (string $middleware_ref) => new $middleware_ref,
                $this->middleware
            );;
    }
}
