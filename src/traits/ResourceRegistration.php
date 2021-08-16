<?php

namespace Louiss0\SlimRouteRegistry\Traits;

use Illuminate\Support\Collection;
use Slim\Routing\RouteCollectorProxy;

trait ResourceRegistration

{

    protected  static Collection $route_group_map;

    private static function registerGetAllRoutes(
        string $path,
        string $method_name,
        string $class_name,
        RouteCollectorProxy  $group
    ) {

        # code...

        $get_routes = $group
            ->get("", [$class_name, $method_name])
            ->setName("$path.$method_name");


        return $get_routes;
    }

    private static function registerGetOneRoutes(
        string $path,
        string $method_name,
        string $class_name,
        RouteCollectorProxy  $group
    ) {

        # code...
        $get_routes = $group
            ->get("/{id:\d+}", [$class_name, $method_name])
            ->setName("$path.$method_name");


        return $get_routes;
    }



    private static function registerPutRoutes(
        string $path,
        string $method_name,
        string $class_name,
        RouteCollectorProxy  $group
    ) {
        # code...
        # code...
        $put_routes = $group
            ->put("", [$class_name, $method_name])
            ->setName("$path.$method_name");



        return $put_routes;
    }




    private static function registerPostRoutes(
        string $path,
        string $method_name,
        string $class_name,
        RouteCollectorProxy  $group
    ) {
        # code...
        $post_routes = $group
            ->post("", [$class_name, $method_name])
            ->setName("$path.$method_name");


        return $post_routes;
    }


    private static function registerPatchRoutes(
        string $path,
        string $method_name,
        string $class_name,
        RouteCollectorProxy  $group
    ) {
        # code...
        # code...
        $patch_routes = $group
            ->patch("/{id:\d+}", [$class_name, $method_name])
            ->setName("$path.$method_name");


        return $patch_routes;
    }

    private static function registerDeleteRoutes(
        string $path,
        string $method_name,
        string $class_name,
        RouteCollectorProxy  $group
    ) {
        # code...

        # code...
        $delete_routes = $group
            ->delete("/{id:\d+}", [$class_name, $method_name])
            ->setName("$path.$method_name");



        return $delete_routes;
    }

    private static  function registerRouteMethods(
        string $class_name,
        $attribute_instances,
        RouteCollectorProxy $group
    ) {
        # code...
        self::$route_group_map->each(
            function ($value) use (
                $attribute_instances,
                $class_name,
                $group
            ) {

                [
                    "method_name" => $method_name,
                    "path" => $path,
                    "name" => $name,
                    "callback" => $callback,
                ] = $value;



                $current_route = $group
                    ->$method_name($path, [$class_name, $callback])
                    ->setName($name);


                self::registerMiddlewareThatUsesTheMiddlwareInterface(
                    $attribute_instances,
                    $current_route
                );

                self::registerMiddlewareIfUseMiddlewareOn(
                    $attribute_instances,
                    $current_route,
                    $callback,
                );

                self::registerMiddlewareIfUseMiddleWareExceptFor(
                    $attribute_instances,
                    $current_route,
                    $callback,
                );
            }
        );
    }
}
