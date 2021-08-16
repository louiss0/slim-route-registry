<?php

namespace Louiss0\SlimRouteRegistry\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class RouteMethod
{

    public function __construct(
        private string $path,
        private string $name,
        private string $method,

    ) {
    }

    public function getPath()
    {
        # code...
        return $this->path;
    }

    public function getName()
    {
        # code...
        return $this->name;
    }

    /**
     * Get the value of method
     */
    public function getMethod()
    {
        return $this->method;
    }
}
