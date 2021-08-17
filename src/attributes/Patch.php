<?php


namespace Louiss0\SlimRouteRegistry\Attributes;

use Attribute;
use Louiss0\SlimRouteRegistry\Enums\RouteMethodNames;

#[Attribute(Attribute::TARGET_METHOD)]
final class Patch extends RouteMethod
{


    public function __construct(
        private $path,
        private $name
    ) {


        parent::__construct($this->path, $this->name, RouteMethodNames::PATCH);
    }
}
