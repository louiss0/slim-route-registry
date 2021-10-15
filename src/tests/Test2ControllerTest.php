<?php

use DI\Bridge\Slim\Bridge;
use Louiss0\SlimRouteRegistry\Controllers\Test2Controller;
use Louiss0\SlimRouteRegistry\RouteRegistry;
use PHPUnit\Framework\TestCase;
use Slim\Interfaces\RouteInterface;

class Test2ControllerTest extends TestCase
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

        RouteRegistry::resource("", Test2Controller::class);


        $route_info = RouteRegistry::getRoutes();

        $changed_info = array_map(
            function (RouteInterface $value) {
                # code...

                return [$value->getMethods(), $value->getCallable()];
            },
            $route_info
        );

        $expected = [
            "route0" => [["GET"], [Test2Controller::class, Test2Controller::GET_ALL]],
            "route1" => [["GET"], [Test2Controller::class, Test2Controller::GET_ONE]],
            "route2" => [["POST"], [Test2Controller::class, Test2Controller::CREATE]],
            "route3" => [["PUT"], [Test2Controller::class, Test2Controller::UPDATE_OR_CREATE_ONE]],
            "route4" => [["PATCH"], [Test2Controller::class, Test2Controller::UPDATE_ONE]],
            "route5" => [["DELETE"], [Test2Controller::class, Test2Controller::DESTROY_ONE]],
        ];

        $this->assertSame($expected, $changed_info);
    }


    public function testAutomaticRouteMethodsAreRegisteredProperlyUnderAGroup()
    {
        # code...


        $this->setupRouteRegistry();


        RouteRegistry::group("/api", function () {

            RouteRegistry::resource("/test", Test2Controller::class);
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
            "route0" => ["/api/test", [Test2Controller::class, Test2Controller::GET_ALL]],
            "route1" => ["/api/test/{id:\d+}", [Test2Controller::class, Test2Controller::GET_ONE]],
            "route2" => ["/api/test", [Test2Controller::class, Test2Controller::CREATE]],
            "route3" => ["/api/test", [Test2Controller::class, Test2Controller::UPDATE_OR_CREATE_ONE]],
            "route4" => ["/api/test/{id:\d+}", [Test2Controller::class, Test2Controller::UPDATE_ONE]],
            "route5" => ["/api/test/{id:\d+}", [Test2Controller::class, Test2Controller::DESTROY_ONE]],
        ];

        $this->assertSame($expected, $changed_info);
    }
}
