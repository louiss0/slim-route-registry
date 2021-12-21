<?php


namespace Louiss0\SlimRouteRegistry\Contracts;

interface UseMiddlewareOnMethodsContract
{
    function getMethodNames(): array;
    function getMiddleware(): array;
}
