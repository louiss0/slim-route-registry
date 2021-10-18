<?php


declare(strict_types=1);

namespace Louiss0\SlimRouteRegistry\Controllers;

use Louiss0\SlimRouteRegistry\Attributes\{
    Delete,
    Get,
    Patch,
    Post,
    Put,
    UseMiddleWareExceptFor,
    UseMiddleWareOn,
};

use Louiss0\SlimRouteRegistry\Middleware\{
    TestMiddleware,
    Test2Middleware
};

use Slim\Http\Response;
use Slim\Http\ServerRequest;



#[
    UseMiddleWareOn(["getAll"], [Test2Middleware::class]),
    UseMiddleWareExceptFor(["getAll"], [TestMiddleware::class])
]
class Test2Controller
{




    const GET_ALL = "getAll";
    const GET_ONE = "getOne";
    const CREATE = "create";
    const UPDATE_OR_CREATE_ONE = "updateOrCreate";
    const UPDATE_ONE = "updateOne";
    const DESTROY_ONE = "destroyOne";




    #[Get("", "get.all"),]
    public function getAll(ServerRequest $request, Response $response): Response
    {
        # code...

        return $response->withJson(data: ["message" => "Did it "]);
    }


    #[Get("/{id:\d+}", "get.one")]
    public function getOne(int $id, Response $response): Response
    {
        # code...

        return $response->withJson(data: ["message" => "Did it "]);
    }

    #[Post("", "create")]
    public function create(ServerRequest $request, Response $response): Response
    {
        # code...

        return $response->withJson(data: ["message" => "Did it "]);
    }

    #[Put("", "update.or.create")]
    public function updateOrCreate(ServerRequest $request, Response $response): Response
    {
        # code...

        return $response->withJson(data: ["message" => "Did it "]);
    }

    #[Patch("/{id:\d+}", "update.one")]
    public function updateOne(int $id, Response $response): Response
    {
        # code...

        return $response->withJson(data: ["message" => "Did it "]);
    }

    #[Delete("/{id:\d+}", "destroy.one")]
    public function destroyOne(int $id, Response $response): Response
    {
        # code...
        return $response;
    }
}
