<?php

declare(strict_types=1);

use DI\Bridge\Slim\Bridge;
use DI\Container;
use Louiss0\SlimRouteRegistry\Controllers\TestController;
use Louiss0\SlimRouteRegistry\RouteRegistry;
use PHPUnit\Framework\TestCase;
use Slim\Interfaces\RouteInterface;

class TestControllerTest extends TestCase
{


    private function getRoutesRegisteredFromTestController()
    {
        # code...

        $app = $this->getApp();
        $res = RouteRegistry::setup($app);

        RouteRegistry::resource("/test", TestController::class);



        $routes = collect($res->getRouteCollector()->getRoutes());


        return $routes;
    }

    private function getApp()
    {
        # code...

        return Bridge::create(new Container());
    }

    public function test_insert_store_update_upsert_destroy_are_registered()
    {

        # code...


        $routes = $this->getRoutesRegisteredFromTestController();

        $callables = $routes->map(function (RouteInterface $value) {

            return $value->getCallable();
        });


        $this->assertSame(actual: $callables->all(), expected: [
            "route0" => [TestController::class, "index"],
            "route1" => [TestController::class, "show"],
            "route2" => [TestController::class, "store"],
            "route3" => [TestController::class, "upsert"],
            "route4" => [TestController::class, "update"],
            "route5" => [TestController::class, "destroy"],
        ]);
    }



    public function test_insert_store_update_upsert_destroy_have_their_names_set_using_path_and_method_name()
    {
        # code...

        $routes = $this->getRoutesRegisteredFromTestController();


        $routeNames = $routes->map(function (RouteInterface $value) {

            return $value->getName();
        })->all();

        $expectedRouteNames = [
            "route0" => "test.index",
            "route1" => "test.show",
            "route2" => "test.store",
            "route3" => "test.upsert",
            "route4" => "test.update",
            "route5" => "test.destroy",
        ];


        $this->assertSame($expectedRouteNames, $routeNames);
    }



    public function test_insert_store_update_upsert_destroy_map_to_get_post_put_patch_delete()
    {
        # code...

        $routes = $this->getRoutesRegisteredFromTestController();


        $routeNamesAndMethods = $routes->map(function (RouteInterface $value) {



            return [$value->getName(), $value->getMethods()];
        })->all();


        $expectedRouteNamesAndMethods = [
            "route0" => ["test.index", ["GET"]],
            "route1" => ["test.show", ["GET"]],
            "route2" => ["test.store", ["POST"]],
            "route3" => ["test.upsert", ["PUT"]],
            "route4" => ["test.update", ["PATCH"]],
            "route5" => ["test.destroy", ["DELETE"]],
        ];

        $this->assertSame($expectedRouteNamesAndMethods, $routeNamesAndMethods);
    }


    public function test_insert_store_update_upsert_destroy_from_test_controller_have_test_as_a_pattern_and_id()
    {
        # code...

        $routes = $this->getRoutesRegisteredFromTestController();


        $routePatterns = $routes->map(function (RouteInterface $value) {

            return $value->getPattern();
        })->all();


        $expectedPatterns = [
            "route0" => "/test",
            "route1" => "/test/{id:\d+}",
            "route2" => "/test",
            "route3" => "/test",
            "route4" => "/test/{id:\d+}",
            "route5" => "/test/{id:\d+}",
        ];

        $this->assertSame($expectedPatterns, $routePatterns);
    }
}
