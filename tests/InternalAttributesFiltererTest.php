<?php

use Louiss0\SlimRouteRegistry\Attributes\{
    RouteMethod,
    UseMiddleWareExceptFor,
    UseMiddleWareOn
};
use Louiss0\SlimRouteRegistry\Classes\{InternalAttributesFilterer};
use Louiss0\SlimRouteRegistry\Mocks\Controllers\TestController;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\MiddlewareInterface;

class InternalAttributesFiltererTest extends TestCase
{



    public function initController(string $class_name)
    {

        return new ReflectionClass($class_name);
    }


    public function getInternalAttributesFilterer()
    {
        # code...

        return new InternalAttributesFilterer();
    }


    public function testUseMiddlewareAttributesAreGathered()
    {
        # code...


        [$controller, $internal_attributes_filterer] = [
            $this->initController(TestController::class),
            $this->getInternalAttributesFilterer()
        ];


        $instances =  array_map(
            callback: fn (ReflectionAttribute $reflectionAttribute) =>
            $reflectionAttribute->newInstance(),
            array: $controller->getAttributes()
        );

        $use_middleware_attributes = $internal_attributes_filterer
            ->findMiddlewareUsedAsAttributes(...$instances);

        $this->assertContainsOnlyInstancesOf(
            MiddlewareInterface::class,
            $use_middleware_attributes,
        );
    }


    public function testFindsRouteAttributeInstance()
    {
        # code...
        [$controller, $internal_attributes_filterer] = [
            $this->initController(TestController::class),
            $this->getInternalAttributesFilterer()
        ];

        $method = $controller->getMethod("getAll");

        $method_attributes = $method->getAttributes();

        $route_attribute_instance = $internal_attributes_filterer
            ->findRouteMethodAttributeInstance(
                ...array_map(
                    fn (ReflectionAttribute $reflectionAttribute) =>
                    $reflectionAttribute->newInstance(),
                    $method_attributes
                )
            );

        $this->assertInstanceOf(RouteMethod::class, $route_attribute_instance,);
    }

    public function testUseMiddlewareOnAttributesAreGathered()
    {
        [$controller, $internal_attributes_filterer] = [
            $this->initController(TestController::class),
            $this->getInternalAttributesFilterer()
        ];


        $instances =  array_map(
            callback: fn (ReflectionAttribute $reflectionAttribute) => $reflectionAttribute->newInstance(),
            array: $controller->getAttributes()
        );

        $use_middleware_attributes = $internal_attributes_filterer->amassUseMiddlewareOnAttributes(...$instances);


        $this->assertContainsOnlyInstancesOf(UseMiddleWareOn::class, $use_middleware_attributes,);
    }
    public function testUseMiddlewareExceptForAttributesAreGathered()
    {
        [$controller, $internal_attributes_filterer] = [
            $this->initController(TestController::class),
            $this->getInternalAttributesFilterer()
        ];


        $instances =  array_map(
            callback: fn (ReflectionAttribute $reflectionAttribute) => $reflectionAttribute->newInstance(),
            array: $controller->getAttributes()
        );

        $use_middleware_attributes = $internal_attributes_filterer->amassUseMiddlewareExceptForAttributes(...$instances);

        $this->assertContainsOnlyInstancesOf(UseMiddleWareExceptFor::class, $use_middleware_attributes,);
    }
}
