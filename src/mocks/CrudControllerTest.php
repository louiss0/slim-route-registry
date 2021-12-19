<?php

use DI\Bridge\Slim\Bridge;
use Louiss0\SlimRouteRegistry\Classes\GroupManipulator;
use Louiss0\SlimRouteRegistry\Mocks\Controllers\CrudController;
use PHPUnit\Framework\TestCase;
use Slim\Interfaces\RouteInterface;

class CrudControllerTest extends TestCase
{


    public function getApp()
    {
        # code...
        return Bridge::create();
    }


    public function setupGroupManipulator()
    {
        # code...

        return new GroupManipulator($this->getApp());
    }


    public function testAutomaticRouteMethodsAreRegisteredProperly()
    {
        # code...


        $group_manipulator = $this->setupGroupManipulator();


        $group_manipulator->resetInnerAndOuterGroupsAndCallClosureFromWithinGroupCreationClosure();

        $route_info = $group_manipulator->getInner_group()
            ->getRouteCollector()
            ->getRoutes();

        $changed_info = array_map(
            function (RouteInterface $value) {
                # code...

                return [$value->getMethods(), $value->getCallable()];
            },
            $route_info
        );

        $expected = [
            "route0" => [["GET"], [CrudController::class, "collect"]],
            "route1" => [["GET"], [CrudController::class, "show"]],
            "route2" => [["POST"], [CrudController::class, "store"]],
            "route3" => [["PUT"], [CrudController::class, "upsert"]],
            "route4" => [["PATCH"], [CrudController::class, "update"]],
            "route5" => [["DELETE"], [CrudController::class, "destroy"]],
        ];

        $this->assertSame($expected, $changed_info);
    }


    public function testAutomaticRouteMethodsAreRegisteredProperlyUnderAGroup()
    {
        # code...


        $this->setupGroupManipulator();


        RouteRegistry::group("/api", function () {

            RouteRegistry::resource("/test", CrudController::class);
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
            "route0" => ["/api/test", [CrudController::class, "collect"]],
            "route1" => ["/api/test/{id:\d+}", [CrudController::class, "show"]],
            "route2" => ["/api/test", [CrudController::class, "store"]],
            "route3" => ["/api/test", [CrudController::class, "upsert"]],
            "route4" => ["/api/test/{id:\d+}", [CrudController::class, "update"]],
            "route5" => ["/api/test/{id:\d+}", [CrudController::class, "destroy"]],
        ];

        $this->assertSame($expected, $changed_info);
    }
}
