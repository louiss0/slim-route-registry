<?php

namespace Louiss0\SlimRouteRegistry\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class UseMiddleWareExceptFor
{



    public function __construct(
        private array $method_names,
        private array $middleware
    ) {
    }

    /**
     * Get the value of methods
     */
    public function getMethodNames()
    {
        return $this->method_names;
    }

    /**
     * Get the value of middleware
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }
}
