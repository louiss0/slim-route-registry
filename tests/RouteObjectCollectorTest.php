<?php

use Louiss0\SlimRouteRegistry\Attributes\RouteMethod;
use Louiss0\SlimRouteRegistry\Attributes\UseMiddleWareExceptFor;
use Louiss0\SlimRouteRegistry\Attributes\UseMiddleWareOn;
use Louiss0\SlimRouteRegistry\Classes\RouteObjectCollector;
use Louiss0\SlimRouteRegistry\Enums\RouteMethodNames;
use Louiss0\SlimRouteRegistry\Mocks\Attributes\{MockUseMiddleWareExceptFor, MockUseMiddleWareOn};
use Louiss0\SlimRouteRegistry\Mocks\Middleware\{Test2Middleware, Test3Middleware, TestMiddleware};
use Louiss0\SlimRouteRegistry\Mocks\Controllers\CrudController;
use PHPUnit\Framework\TestCase;


class RouteObjectCollectorTest extends TestCase

{


    public function getRouteObjectCollector()
    {
        return new RouteObjectCollector();
    }


    public function mockRouteObject(
        string $class_name,
        string $method_name,
        string $route_name,
        string $callback_name,
        array $middleware,
        string $path = "",
    ) {



        return compact(
            "class_name",
            "method_name",
            "route_name",
            "callback_name",
            "middleware",
            "path",
        );
    }


    public function mockRouteObjects(
        string $class_name,
        string $method_name,
        string $route_name,
        string $callback_name,
        array $middleware,
        string $path = "",
    ) {

        return [
            compact(
                "class_name",
                "method_name",
                "route_name",
                "callback_name",
                "middleware",
                "path",
            )
        ];
    }


    public function getMockRouteObjectsAfterUseMiddleWareOnAttributeIsUsed()
    {

        return array_map(
            callback: function (array $route_group_object) {

                return match ($route_group_object["callback_name"]) {

                    "show", "collect" =>
                    array_merge($route_group_object, ["middleware" => [TestMiddleware::class]]),
                    "store", "destroy" =>
                    array_merge($route_group_object, ["middleware" => [Test2Middleware::class]]),
                    default => $route_group_object,
                };
            },
            array: $this->getCrudMockObjects()
        );
    }


    public function getMockRouteObjectsAfterUseMiddleWareExecptForAttributeIsUsed()
    {

        return array_map(
            callback: function (array $route_group_object) {

                return match ($route_group_object["callback_name"]) {

                    "show", "collect" => array_merge($route_group_object, ["middleware" => [TestMiddleware::class]]),

                    "store", "destroy" =>
                    array_merge($route_group_object, ["middleware" => [Test3Middleware::class]]),


                    default => $route_group_object,
                };
            },
            array: $this->getCrudMockObjects()
        );
    }


    public function getCrudMockObjects()
    {


        return [
            $this->mockRouteObject(
                class_name: CrudController::class,
                method_name: RouteMethodNames::GET,
                route_name: str_pad(CrudController::COLLECT, strlen(CrudController::COLLECT) + 1, ".", STR_PAD_LEFT),
                callback_name: CrudController::COLLECT,
                middleware: []

            ),
            $this->mockRouteObject(
                class_name: CrudController::class,
                method_name: RouteMethodNames::GET,
                route_name: str_pad(CrudController::SHOW, strlen(CrudController::SHOW) + 1, ".", STR_PAD_LEFT),
                callback_name: CrudController::SHOW,
                middleware: [],
                path: "/{id:\d+}"
            ),
            $this->mockRouteObject(
                class_name: CrudController::class,
                method_name: RouteMethodNames::POST,
                route_name: str_pad(CrudController::STORE, strlen(CrudController::STORE) + 1, ".", STR_PAD_LEFT),
                callback_name: CrudController::STORE,
                middleware: []

            ),
            $this->mockRouteObject(
                class_name: CrudController::class,
                method_name: RouteMethodNames::PATCH,
                route_name: str_pad(CrudController::UPDATE, strlen(CrudController::UPDATE) + 1, ".", STR_PAD_LEFT),
                callback_name: CrudController::UPDATE,
                middleware: [],
                path: "/{id:\d+}"

            ),
            $this->mockRouteObject(
                class_name: CrudController::class,
                method_name: RouteMethodNames::DELETE,
                route_name: str_pad(CrudController::DESTROY, strlen(CrudController::DESTROY) + 1, ".", STR_PAD_LEFT),
                callback_name: CrudController::DESTROY,
                middleware: [],
                path: "/{id:\d+}"

            ),

        ];
    }




    public function getMockRouteObjectsAfterUseMiddleWareOnAndUseMiddleWareExecptForAttributeIsUsed()
    {
        return array_map(
            callback: function (array $route_group_object) {

                return match ($route_group_object["callback_name"]) {

                    "show", "collect" =>
                    array_merge($route_group_object, ["middleware" => [TestMiddleware::class, Test3Middleware::class]]),
                    "store", "destroy" =>
                    array_merge($route_group_object, ["middleware" => [Test2Middleware::class]]),
                    "update" =>
                    array_merge($route_group_object, ["middleware" => [Test3Middleware::class]]),
                    default => $route_group_object,
                };
            },
            array: $this->getCrudMockObjects()
        );
    }




    //! Tests

    function testRouteObjectIsInsertedIntoRouteObjects()
    {

        $route_object_collector = $this->getRouteObjectCollector();

        $mock_route_object = $this->mockRouteObject(
            class_name: CrudController::class,
            method_name: CrudController::COLLECT,
            route_name: CrudController::COLLECT,
            callback_name: CrudController::COLLECT,
            middleware: []

        );

        $route_object_collector->addRouteNecessitiesToRouteObject(
            $mock_route_object["class_name"],
            $mock_route_object["method_name"],
            $mock_route_object["route_name"],
            $mock_route_object["callback_name"],
            $mock_route_object["middleware"],
        );

        $route_objects = $route_object_collector->getRoute_group_objects();

        $this->assertEquals($route_objects, [$mock_route_object]);
    }


    public function testRouteObjectIsAddedBasedOnMethodName()
    {
        # code...
        $route_object_collector = $this->getRouteObjectCollector();

        $crud_mock_objects = $this->getCrudMockObjects();

        $incoming_methods = array_map(
            fn (array $crud_mock_object) =>
            array_diff_key($crud_mock_object, array_flip(["route_name", "method_name"])),
            $crud_mock_objects
        );


        array_walk(
            callback: fn (array $incoming_method) =>
            $route_object_collector->addRouteRouteGroupObjectBasedOnCallbackName(
                '',
                $incoming_method["class_name"],
                $incoming_method["callback_name"],
                $incoming_method["middleware"],
            ),
            array: $incoming_methods
        );

        $route_objects = $route_object_collector->getRoute_group_objects();

        $this->assertEquals($route_objects, $crud_mock_objects);
    }


    // public function testAltersRouteGroupobjectsBasedOnUseMiddlewareOnAttributes()
    // {
    //     $route_object_collector = $this->getRouteObjectCollector();

    //     $crud_mock_objects = $this->getCrudMockObjects();

    //     $incoming_methods = array_map(
    //         fn (array $crud_mock_object) =>
    //         array_diff_key($crud_mock_object, array_flip(["route_name", "method_name"])),
    //         $crud_mock_objects
    //     );


    //     array_walk(
    //         callback: fn (array $incoming_method) =>
    //         $route_object_collector->addRouteRouteGroupObjectBasedOnCallbackName(
    //             "",
    //             $incoming_method["class_name"],
    //             $incoming_method["callback_name"],
    //             $incoming_method["middleware"],
    //         ),
    //         array: $incoming_methods
    //     );

    //     $route_object_collector->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareOnAttributes(
    //         new UseMiddleWareOn(
    //             ["collect", "show"],
    //             [TestMiddleware::class]
    //         ),
    //         new UseMiddleWareOn(
    //             ["store", "destroy"],
    //             [Test2Middleware::class]
    //         )
    //     );

    //     $route_objects = $route_object_collector->getRoute_group_objects();

    //     $expected = $this->getMockRouteObjectsAfterUseMiddleWareOnAttributeIsUsed();

    //     $this->assertEquals($expected, $route_objects);
    // }

    // public function testAltersRouteGroupobjectsBasedOnUseMiddlewareExecptForAttributes()
    // {

    //     $route_object_collector = $this->getRouteObjectCollector();

    //     $crud_mock_objects = $this->getCrudMockObjects();

    //     $incoming_methods = array_map(
    //         fn (array $crud_mock_object) =>
    //         array_diff_key($crud_mock_object, array_flip(["route_name", "method_name"])),
    //         $crud_mock_objects
    //     );


    //     array_walk(
    //         callback: fn (array $incoming_method) =>
    //         $route_object_collector->addRouteRouteGroupObjectBasedOnCallbackName(
    //             "",
    //             $incoming_method["class_name"],
    //             $incoming_method["callback_name"],
    //             $incoming_method["middleware"],
    //         ),
    //         array: $incoming_methods
    //     );

    //     $route_object_collector->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareExceptForAttributes(
    //         new UseMiddleWareExceptFor(
    //             [
    //                 "update",
    //                 "store",
    //                 "destroy"
    //             ],
    //             [TestMiddleware::class]
    //         ),
    //         new UseMiddleWareExceptFor(
    //             [
    //                 "collect",
    //                 "show",
    //                 "update"
    //             ],
    //             [Test3Middleware::class]
    //         )
    //     );

    //     $route_objects = $route_object_collector->getRoute_group_objects();

    //     $expected = $this->getMockRouteObjectsAfterUseMiddleWareExecptForAttributeIsUsed();

    //     $this->assertEquals($expected, $route_objects);
    // }

    // public function testOnUseMiddlewareExecptForAttributesAreAddedAfterUseMiddlewareOnAttributes()
    // {
    //     $route_object_collector = $this->getRouteObjectCollector();

    //     $crud_mock_objects = $this->getCrudMockObjects();

    //     $incoming_methods = array_map(
    //         fn (array $crud_mock_object) =>
    //         array_diff_key($crud_mock_object, array_flip(["route_name", "method_name"])),
    //         $crud_mock_objects
    //     );


    //     array_walk(
    //         callback: fn (array $incoming_method) =>
    //         $route_object_collector->addRouteRouteGroupObjectBasedOnCallbackName(
    //             "",
    //             $incoming_method["class_name"],
    //             $incoming_method["callback_name"],
    //             $incoming_method["middleware"],
    //         ),
    //         array: $incoming_methods
    //     );


    //     $route_object_collector
    //         ->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareOnAttributes(
    //             new UseMiddleWareOn(
    //                 ["show", "collect"],
    //                 [TestMiddleware::class]
    //             ),
    //             new UseMiddleWareOn(
    //                 ["store", "destroy"],
    //                 [Test2Middleware::class]
    //             ),
    //         )->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareExceptForAttributes(
    //             new UseMiddleWareExceptFor(
    //                 [
    //                     "store",
    //                     "destroy",
    //                 ],
    //                 [Test3Middleware::class]
    //             )
    //         );


    //     $route_objects = $route_object_collector->getRoute_group_objects();


    //     $expected = $this->getMockRouteObjectsAfterUseMiddleWareOnAndUseMiddleWareExecptForAttributeIsUsed();


    //     $this->assertEquals($expected, $route_objects);
    // }
}
