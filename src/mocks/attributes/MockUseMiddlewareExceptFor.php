<?php

namespace Louiss0\SlimRouteRegistry\Mocks\Attributes;

use Louiss0\SlimRouteRegistry\Contracts\UseMiddlewareOnMethodsContract;

final class MockUseMiddleWareExceptFor implements UseMiddlewareOnMethodsContract
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
        return $this->middleware;
    }
}