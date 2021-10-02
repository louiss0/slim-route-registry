<?php

declare(strict_types=1);

namespace  Louiss0\SlimRouteRegistry;

use Closure;
use Exception;
use Louiss0\SlimRouteRegistry\Attributes\{
    RouteMethod,
    UseMiddleWareExceptFor,
    UseMiddleWareOn
};
use Louiss0\SlimRouteRegistry\Utils\Classes\MapCreator;
use Louiss0\SlimRouteRegistry\Utils\Classes\MiddlewareOrganizer;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\Routing\RouteCollectorProxy;
use Louiss0\SlimRouteRegistry\Utils\Traits\{
    RouteRegistration,
};
use Psr\Http\Server\MiddlewareInterface;

final class RouteRegistry
{

    public static App | RouteCollectorProxyInterface $app;

    protected static MapCreator $map_creator;



    use RouteRegistration;

    public final static function setup(
        App | RouteCollectorProxyInterface $app
    ) {
        # code...


        self::$app = $app;

        self::$map_creator = new MapCreator(new MiddlewareOrganizer());
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
    public static function group(string $path, Closure $callable)
    {
        # code...

        $group =  self::$app->group($path, function (RouteCollectorProxyInterface $group) use ($callable) {

            self::setup($group);
            $callable();
        });



        return $group;
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
        $constructor_attribute_instances = collect([]);


        $group = self::$app->group(
            $path,
            function (RouteCollectorProxy $group) use (
                $class_name,
                $path,
                $constructor_attribute_instances
            ) {


                $reflection = new ReflectionClass($class_name);

                $methods =
                    collect($reflection->getMethods());

                $reflection_class_name =
                    $reflection->getName();

                $constructor_attribute_instances =
                    $constructor_attribute_instances
                    ->merge($reflection->getAttributes())
                    ->map(fn (ReflectionAttribute $attribute) =>
                    $attribute->newInstance());

                $use_except_for_middleware_instances =

                    $constructor_attribute_instances->filter(
                        fn (object $class) =>  is_a($class, UseMiddleWareExceptFor::class)
                    );

                $use_on_middleware_instances =

                    $constructor_attribute_instances->filter(
                        fn (object $class) => is_a($class, UseMiddleWareOn::class)
                    );

                $methods->each(
                    function (ReflectionMethod $method) use (
                        $reflection_class_name,
                        $path,
                    ) {
                        # code...

                        $method_attribute_instances = collect($method->getAttributes())
                            ->map(fn (ReflectionAttribute $attribute) => $attribute->newInstance());


                        $route_method_attribute = $method_attribute_instances
                            ->first(fn (object $object) => is_a($object, RouteMethod::class));

                        $middleware_collection = $method_attribute_instances
                            ->filter(fn (object $object) => is_a($object, MiddlewareInterface::class));

                        $method_name = $method->getName();




                        if (!$route_method_attribute) {

                            return self::$map_creator
                                ->addRouteRouteGroupObjectBasedOnMethodName(
                                    path: $path,
                                    class_name: $reflection_class_name,
                                    callback_name: $method_name,
                                    middleware: $method_attribute_instances
                                );
                        }


                        self::$map_creator->addRouteNecessitiesToRouteObject(
                            $reflection_class_name,
                            $route_method_attribute->getMethod(),
                            $route_method_attribute->getName(),
                            $method_name,
                            $middleware_collection,
                            $route_method_attribute->getPath()
                        );
                    }
                );



                self::$map_creator
                    ->generateKeysAndValuesForMiddlewareOnCollection(...$use_on_middleware_instances);

                self::$map_creator
                    ->generateKeysAndValuesForMiddlewareExceptCollection(...$use_except_for_middleware_instances);

                self::$map_creator
                    ->addMiddlewareToRouteMapBasedOnUseMiddlewareOnCollection();

                self::$map_creator
                    ->addMiddlewareToRouteObjectBasedOnUseMiddlewareExceptForCollection();



                self::registerRouteMethods(
                    route_group_objects: self::$map_creator->getRoute_group_objects(),
                    group: $group
                );
            }
        );



        $constructor_attribute_instances
            ->filter(fn (object $class) => is_a($class, MiddlewareInterface::class))
            ->each(fn (MiddlewareInterface $middleware) =>
            $group->addMiddleware($middleware));
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
