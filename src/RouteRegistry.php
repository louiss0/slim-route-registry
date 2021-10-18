<?php

declare(strict_types=1);

namespace  Louiss0\SlimRouteRegistry;

require_once "utils/helpers.php";

use function Louiss0\SlimRouteRegistry\Utils\Helpers\{
    array_every,
    array_first
};

use Closure;
use Exception;
use Louiss0\SlimRouteRegistry\Attributes\{
    RouteMethod,
    UseMiddleWareExceptFor,
    UseMiddleWareOn
};
use Louiss0\SlimRouteRegistry\Enums\AutomaticRegistrationMethodNames;
use Louiss0\SlimRouteRegistry\Classes\{
    GroupManipulator,
    RouteObjectCollector,
    MiddlewareRegistrar
};
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Routing\RouteCollectorProxy;


final class RouteRegistry
{

    private static App $app;


    private static RouteObjectCollector $route_object_collector;


    private static GroupManipulator $group_manipulator;

    public final static function setup(
        App  $app
    ) {
        # code...


        self::$app = $app;

        self::$route_object_collector = new RouteObjectCollector();

        $inner_group = $app;

        $outer_group = self::$app->group("", function (RouteCollectorProxy $group) use (&$inner_group) {

            $inner_group = $group;
        });


        self::$group_manipulator = new GroupManipulator(
            new MiddlewareRegistrar($outer_group)
        );


        self::$group_manipulator
            ->setInner_group($inner_group)
            ->setOuter_group($outer_group);
    }







    public static function getRoutes()
    {



        return self::$app->getRouteCollector()->getRoutes();
    }



    public static function get(string $pattern, callable | array $callable)
    {
        # code...

        return self::$group_manipulator->get($pattern, $callable);
    }


    public static function post(string $pattern, callable | array $callable)
    {
        # code...

        return self::$group_manipulator->post($pattern, $callable);
    }

    public static function patch(string $pattern, callable | array $callable)
    {
        # code...

        return self::$group_manipulator->patch($pattern, $callable);
    }

    public static function delete(string $pattern, callable | array $callable)
    {
        # code...

        return self::$group_manipulator->delete($pattern, $callable);
    }

    public static function put(string $pattern, callable | array $callable)
    {
        # code...

        return self::$group_manipulator->put($pattern, $callable);
    }

    public static function any(string $pattern, callable | array $callable)
    {
        # code...

        return self::$group_manipulator->any($pattern, $callable);
    }

    public static function options(string $pattern, callable | array $callable)
    {
        # code...

        return self::$group_manipulator->options($pattern, $callable);
    }



    /** 
     *  This function registers a route Group 
     * 
     */
    public static function group(string $path, Closure $callable)
    {
        # code...

        $inner_group =
            self::$group_manipulator
            ->getInner_group();

        $outer_group =
            $inner_group->group(
                $path,
                function (RouteCollectorProxyInterface $group) use ($callable, &$inner_group) {


                    self::$group_manipulator
                        ->setInner_group($group);

                    $callable();

                    $inner_group = $group;
                }
            );


        self::$group_manipulator = new GroupManipulator(
            middlewareRegistrar: new MiddlewareRegistrar($outer_group)
        );


        return self::$group_manipulator
            ->setInner_group($inner_group)
            ->setOuter_group($outer_group)
            ->getMiddlewareRegistrar();
    }


    /**  
     *  This method takes a path then a class_name  
     *  a class with the method's of (index | store | destroy | update| upsert | show) will
     *  be used then given a name by using its path as the prefix and the name as the 
     *  post fix
     *  
     * If you want to register new methods use the RouteMethod Attribute on the method you want to register 
     */

    static function resource(string $path, string $class_name): void
    {
        # code...
        $constructor_attribute_instances = [];

        $group = self::$group_manipulator->getInner_group()->group(
            $path,
            function (RouteCollectorProxy $group) use (
                $class_name,
                $path,
                &$constructor_attribute_instances
            ) {

                $reflection = new ReflectionClass($class_name);

                $methods = $reflection->getMethods();

                $reflection_class_name = $reflection->getName();

                $reflection_attributes = $reflection->getAttributes();


                $constructor_attribute_instances = array_merge(
                    $constructor_attribute_instances,
                    $reflection_attributes
                );



                $use_except_for_middleware_instances =
                    array_filter(
                        callback: fn (object $class) =>  is_a($class, UseMiddleWareExceptFor::class),
                        array: $constructor_attribute_instances
                    );

                $use_on_middleware_instances =
                    array_filter(
                        callback: fn (object $class) =>
                        is_a($class, UseMiddleWareOn::class),
                        array: $constructor_attribute_instances
                    );

                $add_route_objects_to_route_group_objects_based_on_data_given =
                    function (ReflectionMethod $method) use (
                        $reflection_class_name,
                        $path,
                    ) {
                        # code...

                        $method_attribute_instances = array_map(
                            callback: fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
                            array: $method->getAttributes()
                        );


                        $middleware_collection =  array_filter(
                            callback: fn (object $object) =>
                            is_a($object, MiddlewareInterface::class),
                            array: $method_attribute_instances
                        );

                        $method_name = $method->getName();

                        $check_if_name_exists_in_automatic_registration_method_names =
                            AutomaticRegistrationMethodNames
                            ::checkIfMethodNameExistsInAutomaticRegistrationMethodNames($method_name);



                        if ($check_if_name_exists_in_automatic_registration_method_names) {

                            return self::$route_object_collector
                                ->addRouteRouteGroupObjectBasedOnMethodName(
                                    path: $path,
                                    class_name: $reflection_class_name,
                                    callback_name: $method_name,
                                    middleware: $middleware_collection
                                );
                        }


                        $route_method_attribute =
                            array_first(
                                callback: fn (object $object) => is_a($object, RouteMethod::class),
                                array: $method_attribute_instances
                            );



                        if ($route_method_attribute) {

                            return self::$route_object_collector
                                ->addRouteNecessitiesToRouteObject(
                                    $reflection_class_name,
                                    $route_method_attribute->getMethod(),
                                    $route_method_attribute->getName(),
                                    $method_name,
                                    $middleware_collection,
                                    $route_method_attribute->getPath()
                                );
                        }
                    };


                array_walk(
                    callback: $add_route_objects_to_route_group_objects_based_on_data_given,
                    array: $methods
                );

                self::$route_object_collector
                    ->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareOnAttributes(
                        ...$use_on_middleware_instances
                    )
                    ->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareExceptForAttributes(
                        ...$use_except_for_middleware_instances
                    );





                self::$group_manipulator
                    ->registerRouteMethods(
                        route_group_objects: self::$route_object_collector
                            ->getRoute_group_objects(),
                        group: $group
                    );


                self::$route_object_collector->flushRouteObjects();
            }
        );


        $middleware_group = array_filter(
            callback: fn (object $class) => is_a($class, MiddlewareInterface::class),
            array: $constructor_attribute_instances
        );



        self::$group_manipulator
            ->setOuter_group($group)
            ->registerMiddleware(...$middleware_group);
    }



    /** 
     * @param ['path'=> string 'resource'=> string ] array $array_of_resource_options 
     * This takes an array of paths and resources if none are passed an error will occur.
     *  If you want to use any middleware on the resources Decorate the methods you want to use them on, Or! 
     * Use the UseMiddlewareExceptFor or UseMiddlewareOn attributes on the resource you want to use it on.  
     */
    public static function resources(...$resource_options): void
    {
        # code...


        $path_and_class_exist_in_resource_options_collection =
            array_every(
                callback: function (array $value) {
                    return array_key_exists("path", $value)
                        && array_key_exists("resource", $value);
                },
                array: $resource_options,
            );


        if (!$path_and_class_exist_in_resource_options_collection) {
            # code...

            throw new Exception(
                "You must pass in a path and a resource as resource options the the resource is a class name ",
                500
            );
        }


        array_walk(
            callback: fn ($resource_option) =>
            self::resource(
                path: $resource_option["path"],
                class_name: $resource_option["resource"]
            ),
            array: $resource_options
        );
    }
}
