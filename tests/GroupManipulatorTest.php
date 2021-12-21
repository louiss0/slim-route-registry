<?php

use DI\Bridge\Slim\Bridge;
use Louiss0\SlimRouteRegistry\Attributes\UseMiddleWareOn;
use Louiss0\SlimRouteRegistry\Classes\{GroupManipulator};
use Louiss0\SlimRouteRegistry\Enums\RouteMethodNames;
use Louiss0\SlimRouteRegistry\Mocks\Controllers\CrudController;
use Louiss0\SlimRouteRegistry\Mocks\Controllers\ResourceController;
use PHPUnit\Framework\TestCase;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Interfaces\RouteInterface;

class GroupManipulatorTest extends TestCase
{



    public function initalizeGroupManipulator()
    {
        $app =  Bridge::create();





        return new GroupManipulator($app);
    }

    // ! Mocks


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

    public function getRouteObjectsCreatedFromCrudController()
    {
        return [
            $this->mockRouteObject(
                class_name: CrudController::class,
                method_name: RouteMethodNames::GET,
                route_name: str_pad(CrudController::COLLECT, strlen(CrudController::COLLECT) + 5, "test.", STR_PAD_LEFT),
                callback_name: CrudController::COLLECT,
                middleware: []

            ),
            $this->mockRouteObject(
                class_name: CrudController::class,
                method_name: RouteMethodNames::GET,
                route_name: str_pad(CrudController::SHOW, strlen(CrudController::SHOW) + 5, "test.", STR_PAD_LEFT),
                callback_name: CrudController::SHOW,
                middleware: [],
                path: "/{id:\d+}"
            ),
            $this->mockRouteObject(
                class_name: CrudController::class,
                method_name: RouteMethodNames::POST,
                route_name: str_pad(CrudController::STORE, strlen(CrudController::STORE) + 5, "test.", STR_PAD_LEFT),
                callback_name: CrudController::STORE,
                middleware: []

            ),
            $this->mockRouteObject(
                class_name: CrudController::class,
                method_name: RouteMethodNames::PATCH,
                route_name: str_pad(CrudController::UPDATE, strlen(CrudController::UPDATE) + 5, "test.", STR_PAD_LEFT),
                callback_name: CrudController::UPDATE,
                middleware: [],
                path: "/{id:\d+}"

            ),
            $this->mockRouteObject(
                class_name: CrudController::class,
                method_name: RouteMethodNames::DELETE,
                route_name: str_pad(CrudController::DESTROY, strlen(CrudController::DESTROY) + 5, "test.", STR_PAD_LEFT),
                callback_name: CrudController::DESTROY,
                middleware: [],
                path: "/{id:\d+}"

            ),
        ];
    }


    public function getRouteObjectsCreatedFromResourceController()
    {
        return [
            $this->mockRouteObject(
                class_name: ResourceController::class,
                method_name: RouteMethodNames::GET,
                route_name: str_pad(ResourceController::COLLECT, strlen(ResourceController::COLLECT) + 5, "test.", STR_PAD_LEFT),
                callback_name: ResourceController::COLLECT,
                middleware: []

            ),
            $this->mockRouteObject(
                class_name: ResourceController::class,
                method_name: RouteMethodNames::GET,
                route_name: str_pad(ResourceController::SHOW, strlen(ResourceController::SHOW) + 5, "test.", STR_PAD_LEFT),
                callback_name: ResourceController::SHOW,
                middleware: [],
                path: "/{id:\d+}"
            ),
            $this->mockRouteObject(
                class_name: ResourceController::class,
                method_name: RouteMethodNames::POST,
                route_name: str_pad(ResourceController::STORE, strlen(ResourceController::STORE) + 5, "test.", STR_PAD_LEFT),
                callback_name: ResourceController::STORE,
                middleware: []

            ),
            $this->mockRouteObject(
                class_name: ResourceController::class,
                method_name: RouteMethodNames::PATCH,
                route_name: str_pad(ResourceController::UPDATE, strlen(ResourceController::UPDATE) + 5, "test.", STR_PAD_LEFT),
                callback_name: ResourceController::UPDATE,
                middleware: [],
                path: "/{id:\d+}"

            ),
            $this->mockRouteObject(
                class_name: ResourceController::class,
                method_name: RouteMethodNames::DELETE,
                route_name: str_pad(ResourceController::DESTROY, strlen(ResourceController::DESTROY) + 5, "test.", STR_PAD_LEFT),
                callback_name: ResourceController::DESTROY,
                middleware: [],
                path: "/{id:\d+}"

            ),
            $this->mockRouteObject(
                class_name: ResourceController::class,
                method_name: RouteMethodNames::PUT,
                route_name: str_pad(ResourceController::UPSERT, strlen(ResourceController::UPSERT) + 5, "test.", STR_PAD_LEFT),
                callback_name: ResourceController::UPSERT,
                middleware: [],
                path: "/{id:\d+}"

            ),
        ];
    }

    public function getExpectedRouteInfoFromCrudController()
    {
        return [
            "route0" => [
                "route_name" => "test.collect",
                "route_pattern" => "/test",
                "route_methods" => ["GET"],
                "route_callable" => [CrudController::class, CrudController::COLLECT],

            ],
            "route1" => [
                "route_name" => "test.show",
                "route_pattern" => "/test/{id:\d+}",
                "route_methods" => ["GET"],
                "route_callable" => [CrudController::class, CrudController::SHOW],

            ],
            "route2" => [
                "route_name" => "test.store",
                "route_pattern" => "/test",
                "route_methods" => ["POST"],
                "route_callable" => [CrudController::class, CrudController::STORE],

            ],
            "route3" => [
                "route_name" => "test.update",
                "route_pattern" => "/test/{id:\d+}",
                "route_methods" => ["PATCH"],
                "route_callable" => [CrudController::class, CrudController::UPDATE],

            ],
            "route4" => [
                "route_name" => "test.destroy",
                "route_pattern" => "/test/{id:\d+}",
                "route_methods" => ["DELETE"],
                "route_callable" => [CrudController::class, CrudController::DESTROY],

            ],

        ];
    }


    public function getExpectedRouteInfoFromResourceController()
    {
        return [
            "route0" => [
                "route_name" => "test.collect",
                "route_pattern" => "/test",
                "route_methods" => ["GET"],
                "route_callable" => [ResourceController::class, ResourceController::COLLECT],

            ],
            "route1" => [
                "route_name" => "test.show",
                "route_pattern" => "/test/{id:\d+}",
                "route_methods" => ["GET"],
                "route_callable" => [ResourceController::class, ResourceController::SHOW],

            ],
            "route2" => [
                "route_name" => "test.store",
                "route_pattern" => "/test",
                "route_methods" => ["POST"],
                "route_callable" => [ResourceController::class, ResourceController::STORE],

            ],
            "route3" => [
                "route_name" => "test.update",
                "route_pattern" => "/test/{id:\d+}",
                "route_methods" => ["PATCH"],
                "route_callable" => [ResourceController::class, ResourceController::UPDATE],

            ],
            "route4" => [
                "route_name" => "test.destroy",
                "route_pattern" => "/test/{id:\d+}",
                "route_methods" => ["DELETE"],
                "route_callable" => [ResourceController::class, ResourceController::DESTROY],

            ],
            "route5" => [
                "route_name" => "test.upsert",
                "route_pattern" => "/test/{id:\d+}",
                "route_methods" => ["PUT"],
                "route_callable" => [ResourceController::class, ResourceController::UPSERT],

            ],
        ];
    }


    //! Utils


    public function generateRouteInfoFromRoutes(array $routes)
    {
        return array_map(function (RouteInterface $route) {
            return [
                "route_name" => $route->getName(),
                "route_pattern" => $route->getPattern(),
                "route_methods" => $route->getMethods(),
                "route_callable" => $route->getCallable(),

            ];
        }, $routes);
    }

    // !Tests

    public function testInitialRouteGroupIsRegistered()
    {
        $group = $this->initalizeGroupManipulator()->getInner_group();

        $this->assertInstanceOf(RouteCollectorProxyInterface::class, $group);
    }



    public function testRouteGroupIsRegistered()
    {

        $group_manipulator = $this->initalizeGroupManipulator();

        /**  @var  RouteCollectorProxyInterface  */
        $inner_group = null;
        $group_manipulator->resetInnerAndOuterGroupsAndCallClosureFromWithinGroupCreationClosure(
            "/test",
            function () use (&$inner_group, $group_manipulator) {
                $inner_group = $group_manipulator->getInner_group();

                $inner_group->get("/{id:\d+}", fn () => null);
                $inner_group->put("/{id:\d+}", fn () => null);
                $inner_group->patch("/{id:\d+}", fn () => null);
            }
        );



        $routes = $inner_group->getRouteCollector()->getRoutes();


        $this->assertNotEmpty($routes);
    }

    public function testRoutesInTheClosureAreResigtered()
    {

        $group_manipulator = $this->initalizeGroupManipulator();

        $group_manipulator
            ->resetInnerAndOuterGroupsAndCallClosureFromWithinGroupCreationClosure(
                "/test",
                function () use ($group_manipulator) {
                    $group =  $group_manipulator->getInner_group();

                    $group->get("/{id:\d+}", function () {
                    });
                }
            );

        $routes = $group_manipulator->getInner_group()->getRouteCollector()->getRoutes();

        $this->assertNotEmpty($routes);
    }


    public function testRouteGroupIsCreatedFromCrudControllerMethodsAndAttributes()
    {
        # code...

        $group_manipulator = $this->initalizeGroupManipulator();

        $group_manipulator->resetInnerAndOuterGroupsAndCallClosureFromWithinGroupCreationClosure("/test", function () use ($group_manipulator) {


            $group_manipulator->setInnerAndOuterSubGroupsBasedOnPath("");
            $group_manipulator->registerRouteMethods($this->getRouteObjectsCreatedFromCrudController());
        });





        $routes = $group_manipulator->getInner_group()->getRouteCollector()->getRoutes();



        $this->assertEquals(
            expected: $this->generateRouteInfoFromRoutes($routes),
            actual: $this->getExpectedRouteInfoFromCrudController()
        );
    }

    public function testRouteGroupIsCreatedFromResourceControllerMethodsAndAttributes()
    {
        $group_manipulator = $this->initalizeGroupManipulator();

        $group_manipulator->resetInnerAndOuterGroupsAndCallClosureFromWithinGroupCreationClosure("/test", function () use ($group_manipulator) {
            # code...
            $group_manipulator->setInnerAndOuterSubGroupsBasedOnPath("");

            $group_manipulator->registerRouteMethods($this->getRouteObjectsCreatedFromResourceController());
        });





        $routes = $group_manipulator->getInner_group()
            ->getRouteCollector()
            ->getRoutes();


        $this->assertEquals(
            expected: $this->generateRouteInfoFromRoutes($routes),
            actual: $this->getExpectedRouteInfoFromResourceController()
        );
    }
}
