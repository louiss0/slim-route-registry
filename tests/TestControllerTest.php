<?php

use DI\Bridge\Slim\Bridge;
use Louiss0\SlimRouteRegistry\Controllers\TestController;
use Louiss0\SlimRouteRegistry\RouteRegistry;
use PHPUnit\Framework\TestCase;
use Slim\Interfaces\RouteInterface;

class TestControllerTest extends TestCase
{


    public function getApp()
    {
        # code...
        return Bridge::create();
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

        RouteRegistry::resource("", TestController::class);


        $route_info = RouteRegistry::getRoutes();

        $changed_info = array_map(
            function (RouteInterface $value) {
                # code...

                return [$value->getMethods(), $value->getCallable()];
            },
            $route_info
        );

        $expected = [
            "route0" => [["GET"], [TestController::class, "index"]],
            "route1" => [["GET"], [TestController::class, "show"]],
            "route2" => [["POST"], [TestController::class, "store"]],
            "route3" => [["PUT"], [TestController::class, "upsert"]],
            "route4" => [["PATCH"], [TestController::class, "update"]],
            "route5" => [["DELETE"], [TestController::class, "destroy"]],
        ];

        $this->assertSame($expected, $changed_info);
    }


    public function testAutomaticRouteMethodsAreRegisteredProperlyUnderAGroup()
    {
        # code...


        $this->setupRouteRegistry();


        RouteRegistry::group("/api", function () {

            RouteRegistry::resource("/test", TestController::class);
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
            "route0" => ["/api/test", [TestController::class, "index"]],
            "route1" => ["/api/test/{id:\d+}", [TestController::class, "show"]],
            "route2" => ["/api/test", [TestController::class, "store"]],
            "route3" => ["/api/test", [TestController::class, "upsert"]],
            "route4" => ["/api/test/{id:\d+}", [TestController::class, "update"]],
            "route5" => ["/api/test/{id:\d+}", [TestController::class, "destroy"]],
        ];

        $this->assertSame($expected, $changed_info);
    }
}
