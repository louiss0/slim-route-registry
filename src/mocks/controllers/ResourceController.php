<?php

namespace Louiss0\SlimRouteRegistry\Mocks\Controllers;

use Louiss0\SlimRouteRegistry\Contracts\ResourceControllerContract;
use Slim\Http\ServerRequest;
use Slim\Http\Response;

class ResourceController implements ResourceControllerContract
{


    function collect(ServerRequest $request, Response $response): Response
    {
        return $response->withJson([]);
    }

    function show(int $id, Response $response): Response
    {
        return $response->withJson([]);
    }

    function store(ServerRequest $request, Response $response): Response
    {
        return $response->withJson([]);
    }


    function upsert(ServerRequest $request, Response $response): Response
    {
        return $response->withJson([]);
    }


    function update(int $id, ServerRequest $request, Response $response): Response
    {


        return $response->withJson([]);
    }


    function destroy(int $id, Response $response): Response
    {
        return $response->withJson([]);
    }
}
