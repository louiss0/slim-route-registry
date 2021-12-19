<?php

namespace Louiss0\SlimRouteRegistry\Attributes;

use Attribute;
use Louiss0\SlimRouteRegistry\Contracts\RouteMethodContract;

#[Attribute(Attribute::TARGET_METHOD)]
class RouteMethod implements RouteMethodContract
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
