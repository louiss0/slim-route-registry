<?php

declare(strict_types=1);

namespace  Louiss0\SlimRouteRegistry;

use Closure;
use Exception;
use Illuminate\Support\Collection;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Routing\RouteCollectorProxy;
use Louiss0\SlimRouteRegistry\Traits\AlterRouteGroupMapTrait;
use Louiss0\SlimRouteRegistry\Traits\MiddlewareRegistration;
use Louiss0\SlimRouteRegistry\Traits\ResourceRegistration;

final class RouteRegistry
{

    public static App | RouteCollectorProxyInterface $app;

    protected static Collection $route_group_map;
    protected static Collection $constructor_attribute_instances;


    use ResourceRegistration,
        MiddlewareRegistration,
        AlterRouteGroupMapTrait;

    public final static function setup(
        App | RouteCollectorProxyInterface $app
    ) {
        # code...

        self::$app = $app;

        self::$route_group_map = collect([]);

        self::$constructor_attribute_instances = collect([]);


        return $app;
    }


    public static function get(string $pattern, callable $callable)
    {
        # code...

        return self::$app->get($pattern, $callable);
    }


    public static function post(string $pattern, callable $callable)
    {
        # code...

        return self::$app->post($pattern, $callable);
    }

    public static function patch(string $pattern, callable $callable)
    {
        # code...

        return self::$app->patch($pattern, $callable);
    }

    public static function delete(string $pattern, callable $callable)
    {
        # code...

        return self::$app->delete($pattern, $callable);
    }

    public static function put(string $pattern, callable $callable)
    {
        # code...

        return self::$app->put($pattern, $callable);
    }

    public static function any(string $pattern, callable $callable)
    {
        # code...

        return self::$app->any($pattern, $callable);
    }

    public static function options(string $pattern, callable $callable)
    {
        # code...

        return self::$app->options($pattern, $callable);
    }



    /** 
     *  This function registers a route Group 
     * 
     */
    public static function group(string $path, Closure $callable)
    {
        # code...

        return self::$app->group($path, function (RouteCollectorProxy $group) use ($callable) {
            # code...

            $callable($group);
        });
    }


    /**  
     *  This method takes a path then a class_name  
     *  a class with the method's of (index | store | destroy | update| upsert | show) will
     *  be used then given a name by using its path as the prefix and the name as the 
     *  post fix
     *  
     * If you want to register new methods use the RouteMethod Attribute on the method you want to register 
     */

    static function resource(string $path, string $class): void
    {
        # code...


        $group = self::$app->group(
            $path,
            function (RouteCollectorProxy $group) use ($class, $path) {


                $path = str_replace("/", "", $path);

                $reflection = new ReflectionClass($class);

                $methods =
                    collect($reflection->getMethods());

                $class_name =
                    $reflection->getName();

                self::$constructor_attribute_instances =
                    self::$constructor_attribute_instances
                    ->merge($reflection->getAttributes())
                    ->map(fn (ReflectionAttribute $attribute) => $attribute->newInstance());


                $methods->each(
                    function (ReflectionMethod $method)
                    use ($class_name, $group, $path) {
                        # code...

                        $methodAttributes = collect($method->getAttributes());
                        $methodName = $method->getName();


                        $current_route = match ($methodName) {

                            "index" =>
                            self::registerGetAllRoutes(
                                $path,
                                $methodName,
                                $class_name,
                                $group
                            ),
                            "show" =>
                            self::registerGetOneRoutes(
                                $path,
                                $methodName,
                                $class_name,
                                $group
                            ),
                            "store" =>
                            self::registerPostRoutes(
                                $path,
                                $methodName,
                                $class_name,
                                $group
                            ),
                            "update" =>
                            self::registerPatchRoutes(
                                $path,
                                $methodName,
                                $class_name,
                                $group
                            ),
                            "upsert" =>
                            self::registerPutRoutes(
                                $path,
                                $methodName,
                                $class_name,
                                $group
                            ),
                            "destroy" =>
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
                                $methodAttributes->map(
                                    fn (ReflectionAttribute $attribute) =>
                                    $attribute->newInstance()
                                ),
                                $current_route
                            );
                            # code...

                            self::registerMiddlewareIfUseMiddlewareOn(
                                self::$constructor_attribute_instances,
                                $current_route,
                                $methodName
                            );



                            return self::registerMiddlewareIfUseMiddleWareExceptFor(
                                self::$constructor_attribute_instances,
                                $current_route,
                                $methodName
                            );
                        }


                        self::alterRouteMapIfRouteMethodAttributeExists(
                            $methodAttributes,
                            self::$route_group_map,
                            $method
                        );
                    }
                );




                self::registerRouteMethods(
                    $class_name,
                    self::$constructor_attribute_instances,
                    $group
                );
            }




        );


        if (self::$constructor_attribute_instances->isNotEmpty()) {

            self::registerMiddlewareThatUsesTheMiddlwareInterface(
                self::$constructor_attribute_instances,
                $group
            );
            # code...
        }
    }



    /** 
     * @param ['path'=> string 'resource'=> string ] array $array_of_resource_options 
     * This takes an array of paths and resources if none are passed an error will occur.
     *  If you want to use any middleware on the resources Decorate the methods you want to use them on, Or! 
     * Use the UseMiddlewareExceptFor or UseMiddlewareOn attributes on the resource you want to use it on.  
     */
    public static function resources(array $array_of_resource_options): void
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
