<?php

namespace Louiss0\SlimRouteRegistry\Contracts;

interface RouteMethodContract
{
    public function getPath(): string;

    public function getName(): string;

    public function getMethod(): string;
}
