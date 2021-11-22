<?php



namespace Louiss0\SlimRouteRegistry\Contracts;

use Slim\Http\Response;
use Slim\Http\ServerRequest;

interface CRUDControllerContract
{

    const INDEX = "index";
    const SHOW = "show";
    const STORE = "store";
    const DESTROY = "destroy";
    const UPDATE = "update";

    public function index(ServerRequest $request, Response $response): Response;
    public function show(int $id, Response $response): Response;
    public function store(ServerRequest $request, Response $response): Response;
    public function update(ServerRequest $request, Response $response): Response;
    public function destroy(int $id, Response $response): Response;
}
