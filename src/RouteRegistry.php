<?php

declare(strict_types=1);

namespace  Louiss0\SlimRouteRegistry;


require_once "utils/helpers.php";

use function Louiss0\SlimRouteRegistry\Utils\Helpers\{
    array_every,
};

use Closure;
use Exception;
use Louiss0\SlimRouteRegistry\Classes\{
    GroupManipulator,
    InternalAttributesFilterer,
    RouteObjectCollector,
};
use Louiss0\SlimRouteRegistry\Enums\AutomaticRegistrationMethodNames;
use Psr\Http\Server\MiddlewareInterface;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;


final class RouteRegistry
{

    private static App $app;


    private static RouteObjectCollector $route_object_collector;


    private static GroupManipulator $group_manipulator;


    private static InternalAttributesFilterer $internal_attributes_filterer;

    public static function setup(
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


    public function groupMiddleware(MiddlewareInterface ...$middleware): void
    {
        self::$group_manipulator->groupMiddleware(...$middleware);
    }

    /**  
     *  This method takes a path then a class_name  
     *  a class with the method's of (collect | store | destroy | update| upsert | show) will
     *  be used then given a name by using its path as the prefix and the name as the 
     *  post fix
     *  
     * If you want to register new methods use the RouteMethod Attribute on the method you want to register 
     */

    static function resource(string $path, string $class_name): void
    {
        # code...

        self::$group_manipulator->setInnerAndOuterSubGroupsBasedOnPath($path);


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


        $constructor_attribute_instances = array_map(
            fn (ReflectionAttribute $attribute) =>
            $attribute->newInstance(),
            $reflection_attributes
        );

        [
            $use_middleware_instance,
            $use_middleware_on_attributes,
            $use_middleware_except_for_attributes
        ] = [
            self::$internal_attributes_filterer->findUseMiddlewareAttribute(...$constructor_attribute_instances),
            self::$internal_attributes_filterer->amassUseMiddlewareOnAttributes(...$constructor_attribute_instances),
            self::$internal_attributes_filterer->amassUseMiddlewareExceptForAttributes(...$constructor_attribute_instances),
        ];

        array_walk(
            callback: function (ReflectionMethod $method) use ($path, $reflection_class_name) {

                [$method_name, $method_attributes] = [
                    $method->getName(),
                    $method->getAttributes()
                ];

                $method_attribute_instances = array_map(
                    fn (ReflectionAttribute $attribute) =>
                    $attribute->newInstance(),
                    $method_attributes
                );

                [$route_method_instance, $use_middleware_instance] = [
                    self::$internal_attributes_filterer->findRouteMethodAttributeInstance(...$method_attribute_instances),
                    self::$internal_attributes_filterer->findUseMiddlewareAttribute(...$method_attribute_instances),
                ];

                $method_name_exists_in_automatic_registration_method_names =
                    AutomaticRegistrationMethodNames::checkIfMethodNameExistsInAutomaticRegistrationMethodNames($method_name);

                if (!$route_method_instance && $method_name_exists_in_automatic_registration_method_names) {


                    if ($use_middleware_instance) {
                        # code...
                        return self::$route_object_collector
                            ->addRouteRouteGroupObjectBasedOnCallbackName(
                                $path,
                                $reflection_class_name,
                                $method_name,
                                $use_middleware_instance->getMiddleware()
                            );
                    }

                    return self::$route_object_collector
                        ->addRouteRouteGroupObjectBasedOnCallbackName(
                            $path,
                            $reflection_class_name,
                            $method_name,
                        );
                } elseif ($route_method_instance && $method_name_exists_in_automatic_registration_method_names) {


                    throw new Exception("Don't add route methods attributes to automatic registration methods");
                }





                if (!$use_middleware_instance) {



                    return self::$route_object_collector->addRouteNecessitiesToRouteObject(
                        class_name: $reflection_class_name,
                        method_name: $route_method_instance->getMethod(),
                        route_name: $route_method_instance->getName(),
                        callback_name: $method_name,
                        path: $route_method_instance->getPath()
                    );
                }


                self::$route_object_collector->addRouteNecessitiesToRouteObject(
                    class_name: $reflection_class_name,
                    method_name: $route_method_instance->getMethod(),
                    route_name: $route_method_instance->getName(),
                    callback_name: $method_name,
                    middleware: $use_middleware_instance->getMiddleware(),
                    path: $route_method_instance->getPath()
                );
            },
            array: $methods
        );


        if (!$use_middleware_instance) {
            # code...

            self::$route_object_collector
                ->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareOnAttributes(...$use_middleware_on_attributes)
                ->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareExceptForAttributes(...$use_middleware_except_for_attributes);

            self::$group_manipulator
                ->registerRouteMethods(self::$route_object_collector->getRoute_group_objects());

            return;
        }




        self::$route_object_collector
            ->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareOnAttributes(...$use_middleware_on_attributes)
            ->replaceRouteGroupObjectsWithOnesCreatedBasedOnUseMiddlewareExceptForAttributes(...$use_middleware_except_for_attributes);


        self::$group_manipulator
            ->registerRouteMethods(self::$route_object_collector->getRoute_group_objects());



        self::$group_manipulator->subGroupMiddleware(...$use_middleware_instance->getMiddleware());

        self::$route_object_collector->flushRouteObjects();
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
