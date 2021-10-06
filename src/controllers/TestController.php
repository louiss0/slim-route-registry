<?php


declare(strict_types=1);

namespace Louiss0\SlimRouteRegistry\Controllers;

use Louiss0\SlimRouteRegistry\Contracts\ResourceControllerContract;
use Louiss0\SlimRouteRegistry\Middleware\Test2Middleware;
use Louiss0\SlimRouteRegistry\Middleware\TestMiddleware;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

#[Test2Middleware]
class TestController implements ResourceControllerContract
{




    #[TestMiddleware]
    public function index(ServerRequest $request, Response $response): Response
    {
        # code...

        return $response->withJson(data: ["message" => "Did it "]);
    }


    public function show(int $id, Response $response): Response
    {
        # code...

        return $response->withJson(data: ["message" => "Did it "]);
    }


    public function store(ServerRequest $request, Response $response): Response
    {
        # code...

        return $response->withJson(data: ["message" => "Did it "]);
    }

    public function upsert(ServerRequest $request, Response $response): Response
    {
        # code...

        return $response->withJson(data: ["message" => "Did it "]);
    }


    public function update(int $id, Response $response): Response
    {
        # code...

        return $response->withJson(data: ["message" => "Did it "]);
    }


    public function destroy(int $id, Response $response): Response
    {
        # code...
        return $response->withJson(data: ["message" => "good "]);
    }
}
