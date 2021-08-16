<?php

declare(strict_types=1);


namespace Louiss0\SlimRouteRegistry\Contracts;

use Slim\Http\Response;
use Slim\Http\ServerRequest;

interface ResourceControllerContract extends CRUDControllerContract
{

    public const UPSERT = "upsert";
    public function upsert(ServerRequest $request, Response $response): Response;
}
