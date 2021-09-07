<?php

declare(strict_types=1);

use DI\Bridge\Slim\Bridge;
use DI\Container;
use Louiss0\SlimRouteRegistry\Controllers\Test2Controller;
use Louiss0\SlimRouteRegistry\RouteRegistry;
use PHPUnit\Framework\TestCase;
use Slim\Interfaces\RouteInterface;

class Test2ControllerTest extends TestCase
{


    private function getRoutesRegisteredFromTest2Controller()
    {
        # code...

        $app = $this->getApp();
        $res = RouteRegistry::setup($app);

        RouteRegistry::resource("/test", Test2Controller::class);




        $routes = collect(RouteRegistry::getRoutes());


        return $routes;
    }

    private function getApp()
    {
        # code...

        return Bridge::create(new Container());
    }

    public function test_getAll_getOne_create_updateOrCreate_updateOne_deleteOne_are_registered()
    {

        # code...


        $routes = $this->getRoutesRegisteredFromTest2Controller();

        $callables = $routes->map(function (RouteInterface $value) {

            return $value->getCallable();
        })->all();


        $this->assertSame(actual: $callables, expected: [
            "route0" => [Test2Controller::class, "getAll"],
            "route1" => [Test2Controller::class, "getOne"],
            "route2" => [Test2Controller::class, "create"],
            "route3" => [Test2Controller::class, "updateOrCreate"],
            "route4" => [Test2Controller::class, "updateOne"],
            "route5" => [Test2Controller::class, "destroyOne"],
        ]);
    }



    public function test_getAll_getOne_create_updateOrCreate_updateOne_deleteOne_have_their_names_set_using_path_and_method_name()
    {
        # code...

        $routes = $this->getRoutesRegisteredFromTest2Controller();


        $routeNames = $routes->map(function (RouteInterface $value) {

            return $value->getName();
        })->all();

        $expectedRouteNames = [
            "route0" => "get.all",
            "route1" => "get.one",
            "route2" => "create",
            "route3" => "update.or.create",
            "route4" => "update.one",
            "route5" => "destroy.one",
        ];


        $this->assertSame($expectedRouteNames, $routeNames);
    }



    public function test_getAll_getOne_create_updateOrCreate_updateOne_deleteOne_map_to_get_post_put_patch_delete()
    {
        # code...

        $routes = $this->getRoutesRegisteredFromTest2Controller();


        $routeNamesAndMethods = $routes->map(function (RouteInterface $value) {



            return $value->getMethods();
        })->all();


        $expectedRouteNamesAndMethods = [
            "route0" => ["GET"],
            "route1" => ["GET"],
            "route2" => ["POST"],
            "route3" => ["PUT"],
            "route4" => ["PATCH"],
            "route5" => ["DELETE"],
        ];

        $this->assertSame($expectedRouteNamesAndMethods, $routeNamesAndMethods);
    }


    public function test_getAll_getOne_create_updateOrCreate_updateOne_deleteOne_from_test_controller_have_test_as_a_pattern_and_id()
    {
        # code...


        $routes = $this->getRoutesRegisteredFromTest2Controller();


        $routePatterns = $routes->map($this->getGetRoutePatternClosure())->all();


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




    public function getGetRoutePatternClosure()
    {
        # code...

        return function (RouteInterface $value) {


            return $value->getPattern();
        };
    }


    public function testRouteRegistryWillRegisterTestSubRoutesToGroup()
    {
        # code...

        RouteRegistry::group("/api", function () {


            RouteRegistry::resource("/test2", Test2Controller::class);
        });

        $routes = collect(RouteRegistry::getRoutes());



        $routePatterns = $routes->map($this->getGetRoutePatternClosure())->all();


        $expectedPatterns = [
            "route0" => "/test",
            "route1" => "/test/{id:\d+}",
            "route2" => "/test",
            "route3" => "/test",
            "route4" => "/test/{id:\d+}",
            "route5" => "/test/{id:\d+}",
            'route6' => '/api/test2',
            'route7' => '/api/test2/{id:\d+}',
            'route8' => '/api/test2',
            'route9' => '/api/test2',
            'route10' => '/api/test2/{id:\d+}',
            'route11' => '/api/test2/{id:\d+}',
        ];

        $this->assertEquals($expectedPatterns, $routePatterns);
    }
}
