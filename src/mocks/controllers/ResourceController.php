<?php

namespace Louiss0\SlimRouteRegistry\Mocks\Controllers;

use Louiss0\SlimRouteRegistry\Contracts\CRUDControllerContract;
use Slim\Http\ServerRequest;
use Slim\Http\Response;

class ResourceController implements CRUDControllerContract
{


    function collect(ServerRequest $request, Response $response): Response
    {
        return $response->withJson([]);
    }

    function store(ServerRequest $request, Response $response): Response
    {
        return $response->withJson([]);
    }

    function show(int $id, Response $response): Response
    {
        return $response->withJson([]);
    }


    function update(ServerRequest $request, Response $response): Response
    {


        return $response->withJson([]);
    }


    function destroy(int $id, Response $response): Response
    {
        return $response->withJson([]);
    }
}
