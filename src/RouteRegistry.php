<?php

declare(strict_types=1);

namespace  Louiss0\SlimRouteRegistry;

use Closure;
use Exception;
use Illuminate\Support\Collection;
use Louiss0\SlimRouteRegistry\Enums\BasicRouteMethodNames;
use Louiss0\SlimRouteRegistry\Utils\Classes\SuperGroup;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Routing\RouteCollectorProxy;
use Louiss0\SlimRouteRegistry\Utils\Traits\{
    AlterRouteGroupMapTrait,
    MiddlewareRegistration,
    RouteRegistration,
};

final class RouteRegistry
{

    public static App | RouteCollectorProxyInterface $app;

    protected static Collection $route_group_map;

    protected static SuperGroup $super_group;


    use RouteRegistration,
        MiddlewareRegistration,
        AlterRouteGroupMapTrait;

    public final static function setup(
        App | RouteCollectorProxyInterface $app
    ) {
        # code...


        self::$app = $app;


        self::$super_group = new SuperGroup();


        self::$route_group_map = collect([]);
    }



    public static function getRoutes()
    {



        return self::$app->getRouteCollector()->getRoutes();
    }






    public static function get(string $pattern, callable | array $callable)
    {
        # code...

        return self::$app->get($pattern, $callable);
    }


    public static function post(string $pattern, callable | array $callable)
    {
        # code...

        return self::$app->post($pattern, $callable);
    }

    public static function patch(string $pattern, callable | array $callable)
    {
        # code...

        return self::$app->patch($pattern, $callable);
    }

    public static function delete(string $pattern, callable | array $callable)
    {
        # code...

        return self::$app->delete($pattern, $callable);
    }

    public static function put(string $pattern, callable | array $callable)
    {
        # code...

        return self::$app->put($pattern, $callable);
    }

    public static function any(string $pattern, callable | array $callable)
    {
        # code...

        return self::$app->any($pattern, $callable);
    }

    public static function options(string $pattern, callable | array $callable)
    {
        # code...

        return self::$app->options($pattern, $callable);
    }



    /** 
     *  This function registers a route Group 
     * 
     */
    public static function group(string $path, Closure | null $callable = null)
    {
        # code...




        $group  =  self::$app->group($path, function (RouteCollectorProxyInterface $group) use ($callable) {

            self::setup($group);

            self::$super_group->setInnerGroup($group);


            $callable?->call(self::$super_group);
        });



        return self::$super_group->setOuterGroup($group);
    }


    /**  
     *  This method takes a path then a class_name  
     *  a class with the method's of (index | store | destroy | update| upsert | show) will
     *  be used then given a name by using its path as the prefix and the name as the 
     *  post fix
     *  
     * If you want to register new methods use the RouteMethod Attribute on the method you want to register 
     */

    static function resource(string $path, string $class)
    {
        # code...

        $constructor_attribute_instances = collect([]);

        $group = self::$app->group(
            $path,
            function (RouteCollectorProxy $group) use (
                $class,
                $path,
                $constructor_attribute_instances
            ) {


                $path = str_replace("/", "", $path);

                $reflection = new ReflectionClass($class);

                $methods =
                    collect($reflection->getMethods());

                $class_name =
                    $reflection->getName();

                $constructor_attribute_instances =
                    $constructor_attribute_instances
                    ->merge($reflection->getAttributes())
                    ->map(fn (ReflectionAttribute $attribute) =>
                    $attribute->newInstance());


                $methods->each(
                    function (ReflectionMethod $method)
                    use (
                        $class_name,
                        $group,
                        $path,
                        $constructor_attribute_instances
                    ) {
                        # code...

                        $method_attribute_instances = collect($method->getAttributes())
                            ->map(
                                fn (ReflectionAttribute $attribute) =>
                                $attribute->newInstance()
                            );
                        $methodName = $method->getName();


                        $current_route = match ($methodName) {

                            BasicRouteMethodNames::GET_ANY =>
                            self::registerGetAllRoutes(
                                $path,
                                $methodName,
                                $class_name,
                                $group
                            ),
                            BasicRouteMethodNames::GET_ONE =>
                            self::registerGetOneRoutes(
                                $path,
                                $methodName,
                                $class_name,
                                $group
                            ),
                            BasicRouteMethodNames::CREATE_ONE =>
                            self::registerPostRoutes(
                                $path,
                                $methodName,
                                $class_name,
                                $group
                            ),
                            BasicRouteMethodNames::UPDATE_ONE =>
                            self::registerPatchRoutes(
                                $path,
                                $methodName,
                                $class_name,
                                $group
                            ),
                            BasicRouteMethodNames::UPDATE_OR_CREATE_ONE =>
                            self::registerPutRoutes(
                                $path,
                                $methodName,
                                $class_name,
                                $group
                            ),
                            BasicRouteMethodNames::DELETE_ONE =>
                            self::registerDeleteRoutes(
                                $path,
                                $methodName,
                                $class_name,
                                $group
                            ),
                            default => null
                        };



                        if ($current_route) {

                            self::registerMiddlewareThatUsesTheMiddlwareInterface(
                                $method_attribute_instances,
                                $current_route
                            );
                            # code...

                            self::registerMiddlewareIfUseMiddlewareOn(
                                $constructor_attribute_instances,
                                $current_route,
                                $methodName
                            );



                            return self::registerMiddlewareIfUseMiddleWareExceptFor(
                                $constructor_attribute_instances,
                                $current_route,
                                $methodName
                            );
                        }


                        self::alterRouteMapIfRouteMethodAttributeExists(
                            $method_attribute_instances,
                            $class_name,
                            $methodName
                        );
                    }
                );



                self::registerRouteMethods(
                    $class_name,
                    $constructor_attribute_instances,
                    $group
                );

                self::$super_group->setInnerGroup($group);
            }




        );


        if ($constructor_attribute_instances->isNotEmpty()) {

            self::registerMiddlewareThatUsesTheMiddlwareInterface(
                $constructor_attribute_instances,
                $group
            );
            # code...
        }


        return self::$super_group->setOuterGroup($group);
    }



    /** 
     * @param ['path'=> string 'resource'=> string ] array $array_of_resource_options 
     * This takes an array of paths and resources if none are passed an error will occur.
     *  If you want to use any middleware on the resources Decorate the methods you want to use them on, Or! 
     * Use the UseMiddlewareExceptFor or UseMiddlewareOn attributes on the resource you want to use it on.  
     */
    public static function resources(...$array_of_resource_options): void
    {
        # code...


        $resource_options_collection = collect($array_of_resource_options);

        $path_and_class_exist_in_resource_options_collection =
            $resource_options_collection->every(function ($value) {

                return array_key_exists("path", $value)
                    && array_key_exists("resource", $value);
            });


        throw_unless(
            $path_and_class_exist_in_resource_options_collection,
            Exception::class,
            "No path or resource in resources"
        );


        $resource_options_collection->each(
            fn ($value) =>
            self::resource($value["path"], $value["resource"])
        );
    }
}
