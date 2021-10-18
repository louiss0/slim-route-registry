<?php

namespace Louiss0\SlimRouteRegistry\Contracts;

interface MiddlewareRegistrarContract
{


    public function registerMiddleware(string| object ...$middleware): self;
}
