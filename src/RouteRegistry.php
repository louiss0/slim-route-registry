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
    InternalAttributesFilterer,
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


    private static InternalAttributesFilterer $internal_attributes_filterer;

    public final static function setup(
        RouteCollectorProxyInterface  $app
    ) {
        # code...

        self::$app = $app;

        self::$route_object_collector = new RouteObjectCollector();

        self::$group_manipulator = new GroupManipulator(group: $app);

        self::$internal_attributes_filterer = new InternalAttributesFilterer();
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
    public static function group(string $path, Closure $closure): void
    {
        # code...


        self::$group_manipulator->resetInnerAndOuterGroupsAndCallClosureFromWithinGroupCreationClosure(
            $path,
            $closure
        );
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

        self::$group_manipulator->resetInnerAndOuterGroupsAndCallClosureFromWithinGroupCreationClosure($path);

        $reflection = new ReflectionClass($class_name);

        [
            $methods,
            $reflection_class_name,
            $reflection_attributes,
        ] = [
            $reflection->getMethods(),
            $reflection->getName(),
            $reflection->getAttributes(),
        ];
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
