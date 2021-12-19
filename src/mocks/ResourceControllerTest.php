<?php

use DI\Bridge\Slim\Bridge;
use DI\Container;
use Louiss0\SlimRouteRegistry\Mocks\Controllers\ResourceController;
use Louiss0\SlimRouteRegistry\RouteRegistry;
use PHPUnit\Framework\TestCase;
use Slim\Interfaces\RouteInterface;

class ResourceControllerTest extends TestCase
{


    public function getApp()
    {
        # code...
        return Bridge::create(new Container());
    }


    public function setupRouteRegistry()
    {
        # code...

        RouteRegistry::setup($this->getApp());
    }


    public function testAutomaticRouteMethodsAreRegisteredProperly()
    {
        # code...


        $this->setupRouteRegistry();

        RouteRegistry::resource("", ResourceController::class);


        $route_info = RouteRegistry::getRoutes();

        $changed_info = array_map(
            function (RouteInterface $value) {
                # code...

                return [$value->getMethods(), $value->getCallable()];
            },
            $route_info
        );

        $expected = [
            "route0" => [["GET"], [ResourceController::class, ResourceController::GET_ALL]],
            "route1" => [["GET"], [ResourceController::class, ResourceController::GET_ONE]],
            "route2" => [["POST"], [ResourceController::class, ResourceController::CREATE]],
            "route3" => [["PUT"], [ResourceController::class, ResourceController::UPDATE_OR_CREATE_ONE]],
            "route4" => [["PATCH"], [ResourceController::class, ResourceController::UPDATE_ONE]],
            "route5" => [["DELETE"], [ResourceController::class, ResourceController::DESTROY_ONE]],
        ];

        $this->assertSame($expected, $changed_info);
    }


    public function testAutomaticRouteMethodsAreRegisteredProperlyUnderAGroup()
    {
        # code...


        $this->setupRouteRegistry();


        RouteRegistry::group("/api", function () {

            RouteRegistry::resource("/test", ResourceController::class);
        });


        $route_info = RouteRegistry::getRoutes();

        $changed_info = array_map(
            function (RouteInterface $value) {
                # code...

                return [$value->getPattern(), $value->getCallable()];
            },
            $route_info
        );

        $expected = [
            "route0" => ["/api/test", [ResourceController::class, ResourceController::GET_ALL]],
            "route1" => ["/api/test/{id:\d+}", [ResourceController::class, ResourceController::GET_ONE]],
            "route2" => ["/api/test", [ResourceController::class, ResourceController::CREATE]],
            "route3" => ["/api/test", [ResourceController::class, ResourceController::UPDATE_OR_CREATE_ONE]],
            "route4" => ["/api/test/{id:\d+}", [ResourceController::class, ResourceController::UPDATE_ONE]],
            "route5" => ["/api/test/{id:\d+}", [ResourceController::class, ResourceController::DESTROY_ONE]],
        ];

        $this->assertSame($expected, $changed_info);
    }
}
