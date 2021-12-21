<?php


namespace Louiss0\SlimRouteRegistry\Contracts;

interface UseMiddlewareOnMethodsContract extends UseMiddlewareContract
{
    function getMethodNames(): array;
}
