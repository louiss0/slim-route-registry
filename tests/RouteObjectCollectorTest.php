<?php

use Louiss0\SlimRouteRegistry\Attributes\UseMiddleWareExceptFor;
use Louiss0\SlimRouteRegistry\Attributes\UseMiddleWareOn;
use Louiss0\SlimRouteRegistry\Controllers\TestController;
use Louiss0\SlimRouteRegistry\Middleware\Test3Middleware;
use Louiss0\SlimRouteRegistry\Middleware\Test4Middleware;
use Louiss0\SlimRouteRegistry\Middleware\TestMiddleware;
use Louiss0\SlimRouteRegistry\Classes\RouteObjectCollector;
use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Test;

class RouteObjectCollectorTest extends TestCase
{



    private function expectedRouteNecessitiesResult(
        string $class_name = TestController::class,
        string $method_name = "get",
        string $route_name = "test.index",
        string $callback_name = "index",
        array $middleware = [TestMiddleware::class],
        string $path = "",
    ) {
        # code...

        return [
            compact(
                "class_name",
                "method_name",
                "route_name",
                "callback_name",
                "middleware",
                "path"
            ),
        ];
    }


    public function getMockInputFromReflectionClassesAndPath()
    {
        # code...

        return [
            [
                "class_name" => TestController::class,
                "path" => "/test",
                "callback_name" => "index",
                "middleware" => [],
            ],
            [
                "class_name" => TestController::class,
                "path" => "/test",
                "callback_name" => "store",
                "middleware" => [],
            ],
            [
                "class_name" => TestController::class,
                "path" => "/test",
                "callback_name" => "show",
                "middleware" => [],
            ],
            [
                "class_name" => TestController::class,
                "path" => "/test",
                "callback_name" => "upsert",
                "middleware" => [],
            ],
            [
                "class_name" => TestController::class,
                "path" => "/test",
                "callback_name" => "update",
                "middleware" => [],
            ],
            [
                "class_name" => TestController::class,
                "path" => "/test",
                "callback_name" => "destroy",
                "middleware" => [],
            ],
        ];
    }



    public function expectedAddsMiddlewareToRouteRouteGroupObjectsBasedOnMethodNameResult(
        array $methods_and_middleware
    ) {
        # code...


        return array_map(
            callback: function (array $route_group_object) use ($methods_and_middleware) {


                ["callback_name" => $callback_name] = $route_group_object;


                if (array_key_exists($callback_name, $methods_and_middleware)) {
                    # code...

                    $route_group_object["middleware"]
                        = $methods_and_middleware[$callback_name];
                }

                return $route_group_object;
            },
            array: $this->expectedAddsRouteRouteGroupObjectBasedOnMethodNameResult()
        );
    }


    public function expectedAddsRouteRouteGroupObjectBasedOnMethodNameResult()
    {
        # code...

        return [
            [
                "class_name" => TestController::class,
                "method_name" => "get",
                "route_name" => "test.index",
                "callback_name" => "index",
                "middleware" => [],
                "path" => "",
            ],
            [
                "class_name" => TestController::class,
                "method_name" => "post",
                "route_name" => "test.store",
                "callback_name" => "store",
                "middleware" => [],
                "path" => "",
            ],
            [
                "class_name" => TestController::class,
                "method_name" => "get",
                "route_name" => "test.show",
                "callback_name" => "show",
                "middleware" => [],
                "path" => "/{id:\d+}",
            ],
            [
                "class_name" => TestController::class,
                "method_name" => "put",
                "route_name" => "test.upsert",
                "callback_name" => "upsert",
                "middleware" => [],
                "path" => "",
            ],
            [
                "class_name" => TestController::class,
                "method_name" => "patch",
                "route_name" => "test.update",
                "callback_name" => "update",
                "middleware" => [],
                "path" => "/{id:\d+}",
            ],
            [
                "class_name" => TestController::class,
                "method_name" => "delete",
                "route_name" => "test.destroy",
                "callback_name" => "destroy",
                "middleware" => [],
                "path" => "/{id:\d+}",
            ],
        ];
    }

    public function routeObjectCollector()
    {
        # code...

        return new RouteObjectCollector();
    }


    public function testAddsRouteNecessitiesToRouteObject()
    {
        # code...

        $route_object_collector = $this->routeObjectCollector();


        $route_object_collector->addRouteNecessitiesToRouteObject(
            class_name: TestController::class,
            method_name: "get",
            route_name: "test.index",
            callback_name: "index",
            middleware: [TestMiddleware::class],
        );

        $expected = $this->expectedRouteNecessitiesResult();

        $this->assertSame($expected, $route_object_collector->getRoute_group_objects());
    }

    public function testAddsRouteNecessitiesToRouteObjectWithIdFilledIn()
    {
        # code...

        $route_object_collector = $this->routeObjectCollector();


        $route_object_collector->addRouteNecessitiesToRouteObjectWithIdFilledIn(
            class_name: TestController::class,
            method_name: "get",
            route_name: "test.index",
            callback_name: "index",
            middleware: [TestMiddleware::class]
        );


        $expected = $this->expectedRouteNecessitiesResult(path: "/{id:\d+}");

        $this->assertSame($expected, $route_object_collector->getRoute_group_objects());
    }

    public function testAddsRouteRouteGroupObjectBasedOnMethodName()
    {
        # code...

        $route_object_collector = $this->routeObjectCollector();


        $expected = $this->expectedAddsRouteRouteGroupObjectBasedOnMethodNameResult();

        array_walk(
            callback: function (array $route_input) use ($route_object_collector) {
                # code...
                $route_object_collector->addRouteRouteGroupObjectBasedOnMethodName(
                    class_name: $route_input["class_name"],
                    path: "/test",
                    middleware: $route_input["middleware"],
                    callback_name: $route_input["callback_name"],
                );
            },
            array: $expected
        );

        $this->assertSame($expected, $route_object_collector->getRoute_group_objects());
    }

    public function testAddsMiddlewareToRouteMapBasedOnUseMiddlewareOnCollection()
    {
        # code...


        $route_object_collector = $this->routeObjectCollector();


        $expected_route_group_objects = $this->getMockInputFromReflectionClassesAndPath();

        array_walk(
            callback: function (array $route_input) use ($route_object_collector) {
                # code...
                $route_object_collector->addRouteRouteGroupObjectBasedOnMethodName(
                    class_name: $route_input["class_name"],
                    path: $route_input["path"],
                    middleware: $route_input["middleware"],
                    callback_name: $route_input["callback_name"],
                );
            },
            array: $expected_route_group_objects
        );

        $route_object_collector->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareOnAttributes(
            new UseMiddleWareOn(
                ["index", "show"],
                [TestMiddleware::class,]
            ),
            new UseMiddleWareOn(["destroy"], [Test4Middleware::class])
        );


        $expected = $this->expectedAddsMiddlewareToRouteRouteGroupObjectsBasedOnMethodNameResult(
            [
                "index" => [
                    TestMiddleware::class
                ],
                "show" => [
                    TestMiddleware::class
                ],
                "destroy" => [
                    Test4Middleware::class
                ],
            ]
        );


        $this->assertEquals($expected, $route_object_collector->getRoute_group_objects());
    }

    public function testAddsMiddlewareToRouteObjectBasedOnUseMiddlewareExceptForCollection()
    {
        # code...

        $route_object_collector = $this->routeObjectCollector();


        $expected_route_group_objects = $this->getMockInputFromReflectionClassesAndPath();

        array_walk(
            callback: function (array $route_input) use ($route_object_collector) {
                # code...
                $route_object_collector->addRouteRouteGroupObjectBasedOnMethodName(
                    class_name: $route_input["class_name"],
                    path: $route_input["path"],
                    middleware: $route_input["middleware"],
                    callback_name: $route_input["callback_name"],
                );
            },
            array: $expected_route_group_objects
        );

        $route_object_collector->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareExceptForAttributes(
            new UseMiddleWareExceptFor(
                ["index", "show"],
                [Test3Middleware::class,]
            ),
            new UseMiddleWareExceptFor(["destroy"], [Test4Middleware::class]),
            new UseMiddleWareExceptFor(["update"], [TestMiddleware::class])
        );



        $expected = $this->expectedAddsMiddlewareToRouteRouteGroupObjectsBasedOnMethodNameResult(
            [
                "index" => [
                    Test4Middleware::class,
                    TestMiddleware::class,
                ],
                "show" => [
                    Test4Middleware::class,
                    TestMiddleware::class,
                ],
                "store" => [
                    Test3Middleware::class,
                    Test4Middleware::class,
                    TestMiddleware::class,

                ],
                "upsert" => [
                    Test3Middleware::class,
                    Test4Middleware::class,
                    TestMiddleware::class,
                ],
                "update" => [
                    Test3Middleware::class,
                    Test4Middleware::class,
                ],
                "destroy" => [
                    Test3Middleware::class,
                    TestMiddleware::class,
                ],
            ]
        );


        $this->assertEquals($expected, $route_object_collector->getRoute_group_objects());
    }
}
