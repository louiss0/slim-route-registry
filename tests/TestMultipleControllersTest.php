<?php

use DI\Bridge\Slim\Bridge;
use Louiss0\SlimRouteRegistry\Mocks\Controllers\Test2Controller;
use Louiss0\SlimRouteRegistry\Mocks\Controllers\TestController;
use Louiss0\SlimRouteRegistry\RouteRegistry;
use PHPUnit\Framework\TestCase;
use Slim\Interfaces\RouteInterface;

class TestMultipleControllersTest extends TestCase
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



    public function testMultipleControllersAreRegistered()
    {
        # code...


        $this->setupRouteRegistry();



        RouteRegistry::group("/api", function () {
            # code...

            RouteRegistry::resource("/test1", TestController::class);

            RouteRegistry::resource("/test2", Test2Controller::class);
        });


        $routes = RouteRegistry::getRoutes();


        $route_patterns = array_map(
            callback: function (RouteInterface $route) {
                # code...

                return $route->getPattern();
            },
            array: $routes
        );


        $expected = [
            "route0" =>  "/api/test1",
            "route1" =>  "/api/test1/{id:\d+}",
            "route2" =>  "/api/test1",
            "route3" =>  "/api/test1/{id:\d+}",
            "route4" =>  "/api/test1/{id:\d+}",
            "route5" =>  "/api/test2",
            "route6" =>  "/api/test2/{id:\d+}",
            "route7" =>  "/api/test2",
            "route8" =>  "/api/test2/{id:\d+}",
            "route9" =>  "/api/test2/{id:\d+}",

        ];

        $this->assertSame($expected, $route_patterns);
    }
}
