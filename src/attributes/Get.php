<?php


namespace Louiss0\SlimRouteRegistry\Attributes;

use Attribute;
use Louiss0\SlimRouteRegistry\Enums\RouteMethodNames;

#[Attribute(Attribute::TARGET_METHOD)]
final class Get extends RouteMethod
{


    public function __construct(
        private string $path,
        private string $name
    ) {


        parent::__construct($this->path, $this->name, RouteMethodNames::GET);
    }
}
