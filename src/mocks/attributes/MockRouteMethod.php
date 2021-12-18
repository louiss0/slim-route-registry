<?php


namespace Louiss0\SlimRouteRegistry\Mocks\Attributes;

use RouteMethodContract;

class MockRouteMethod implements RouteMethodContract
{

    public function __construct(
        private string $path,
        private string $name,
        private string $method,

    ) {
    }

    public function getPath(): string
    {
        # code...
        return $this->path;
    }

    public function getName(): string
    {
        # code...
        return $this->name;
    }

    /**
     * Get the value of method
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}
