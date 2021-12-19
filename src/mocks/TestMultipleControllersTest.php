<?php

use DI\Bridge\Slim\Bridge;
use Louiss0\SlimRouteRegistry\Controllers\{
    PostController,
    Test2Controller,
    TestController
};
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

            RouteRegistry::resource("/posts", PostController::class);
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
            "route3" =>  "/api/test1",
            "route4" =>  "/api/test1/{id:\d+}",
            "route5" =>  "/api/test1/{id:\d+}",
            "route6" =>  "/api/test2",
            "route7" =>  "/api/test2/{id:\d+}",
            "route8" =>  "/api/test2",
            "route9" =>  "/api/test2",
            "route10" =>  "/api/test2/{id:\d+}",
            "route11" =>  "/api/test2/{id:\d+}",
            "route12" =>  "/api/posts",
            "route13" =>  "/api/posts/{slug:[a-z][a-z\-]+[a-z]}",
            "route14" =>  "/api/posts",
            "route15" =>  "/api/posts",
            "route16" =>  "/api/posts/{slug:[a-z][a-z\-]+[a-z]}",
            "route17" =>  "/api/posts/{slug:[a-z][a-z\-]+[a-z]}",
        ];

        $this->assertSame($expected, $route_patterns);
    }
}
