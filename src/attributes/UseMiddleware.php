<?php


namespace Louiss0\SlimRouteRegistry\Attributes;

use Attribute;
use Exception;
use Psr\Http\Server\MiddlewareInterface;


#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class UseMiddleware
{

    /** @var MiddlewareInterface[]  */
    private $middleware;

    public function __construct(array $middleware)
    {

        $instances = $this->createInstancesFromClassStrings($middleware);
        $this->throwErrorIfOneOfTheClassesIsNotAnInstanceOfTheMiddlewareInterface($instances);

        $this->middleware = $instances;
    }

    /**
     * Get the value of middleware
     *  @return MiddlewareInterface[]
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }


    private function createInstancesFromClassStrings(array $array_of_strings)
    {


        return array_map(
            fn (string $string) =>
            class_exists($string) ? new $string : throw new Exception("{$string} does not exist as a class"),
            $array_of_strings
        );
    }


    private function throwErrorIfOneOfTheClassesIsNotAnInstanceOfTheMiddlewareInterface(array  $array_of_objects)
    {

        array_walk(
            callback: fn (object $class) =>
            is_a($class, MiddlewareInterface::class) ? $class : throw new Exception("{$class::class} is not a slim middleware"),
            array: $array_of_objects
        );
    }
}
