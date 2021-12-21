<?php


namespace Louiss0\SlimRouteRegistry\Attributes;

use Attribute;
use Louiss0\SlimRouteRegistry\Contracts\UseMiddlewareContract;
use Psr\Http\Server\MiddlewareInterface;


#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class UseMiddleware implements UseMiddlewareContract
{



    public function __construct(private array $middleware)
    {
    }

    /**
     * Get the value of middleware
     *  @return MiddlewareInterface[]
     */
    public function getMiddleware(): array
    {
        return array_map(
            fn (string $middleware_ref) => new $middleware_ref,
            $this->middleware
        );
    }
}
