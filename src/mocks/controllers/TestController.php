<?php


namespace Louiss0\SlimRouteRegistry\Mocks\Controllers;

use Louiss0\SlimRouteRegistry\Attributes\{
    Delete,
    Get,
    Patch,
    Post,
    RouteMethod,
    UseMiddleware,
    UseMiddleWareExceptFor,
};
use Louiss0\SlimRouteRegistry\Mocks\Middleware\{
    Test2Middleware,
    Test3Middleware,
    TestMiddleware
};

#[
    UseMiddleware([TestMiddleware::class]),
    UseMiddleWareExceptFor(["collect", "update"], [Test2Middleware::class]),
]
class TestController
{


    #[
        RouteMethod("", "get.all", "get"),
        UseMiddleware([Test3Middleware::class]),
    ]
    function getAll()
    {
    }

    #[
        Get("/{id:\d+}", "get.one",),
        UseMiddleware([Test3Middleware::class]),
    ]
    function getOne()
    {
    }
    #[
        Post("", "create",),
        UseMiddleware([Test3Middleware::class]),
    ]
    function create()
    {
    }


    #[
        Patch("/{id:\d+}", "update.one",),
        UseMiddleware([Test3Middleware::class]),
    ]
    function updateOne()
    {
    }

    #[
        Delete("/{id:\d+}", "delete",),
        UseMiddleware([Test3Middleware::class]),
    ]
    function deleteOne()
    {
    }
}
