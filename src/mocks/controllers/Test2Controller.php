<?php


namespace Louiss0\SlimRouteRegistry\Mocks\Controllers;

use Louiss0\SlimRouteRegistry\Attributes\{
    Delete,
    Get,
    Patch,
    Post,
    RouteMethod,
};


class Test2Controller
{


    #[
        RouteMethod("", "get.all", "get"),
    ]
    function getAll()
    {
    }

    #[
        Get("/{id:\d+}", "get.one",),

    ]
    function getOne()
    {
    }
    #[
        Post("", "create",),

    ]
    function create()
    {
    }
    #[
        Patch("/{id:\d+}", "update.one",),

    ]
    function updateOne()
    {
    }
    #[
        Delete("/{id:\d+}", "delete",),

    ]
    function deleteOne()
    {
    }
}
