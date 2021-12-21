<?php


namespace Louiss0\SlimRouteRegistry\Mocks\Controllers;

use Louiss0\SlimRouteRegistry\Attributes\{
    Delete,
    Get,
    Patch,
    Post,
    RouteMethod,
    UseMiddleWareExceptFor,
};
use Louiss0\SlimRouteRegistry\Mocks\Middleware\{
    Test2Middleware,
    Test3Middleware,
    TestMiddleware
};

#[
    UseMiddleWareExceptFor(["collect", "update"], [Test2Middleware::class]),
    TestMiddleware,
]
class TestController
{


    #[
        RouteMethod("", "get.all", "get"),
        Test3Middleware,
    ]
    function getAll()
    {
    }

    #[
        Get("/{id:\d+}", "get.one",),
        Test3Middleware,
    ]
    function getOne()
    {
    }
    #[
        Post("", "create",),
        Test3Middleware,
    ]
    function create()
    {
    }


    #[
        Patch("/{id:\d+}", "update.one",),
        Test3Middleware,
    ]
    function updateOne()
    {
    }

    #[
        Delete("/{id:\d+}", "delete",),
        Test3Middleware,
    ]
    function deleteOne()
    {
    }
}
