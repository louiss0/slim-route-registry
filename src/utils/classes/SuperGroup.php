<?php


namespace Louiss0\SlimRouteRegistry\Utils\Classes;

use Closure;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Interfaces\RouteGroupInterface;
use Slim\Routing\RouteCollectorProxy;

class SuperGroup
{

    private RouteCollectorProxy $innerGroup;
    private RouteGroupInterface $outerGroup;


    public function get(string $pattern, callable | array $callable)
    {
        # code...


        return $this->innerGroup->get($pattern, $callable);
    }


    public function post(string $pattern, callable | array $callable)
    {
        # code...

        return $this->innerGroup->post($pattern, $callable);
    }

    public function patch(string $pattern, callable | array $callable)
    {
        # code...

        return $this->innerGroup->patch($pattern, $callable);
    }

    public function delete(string $pattern, callable | array $callable)
    {
        # code...

        return $this->innerGroup->delete($pattern, $callable);
    }

    public function put(string $pattern, callable | array $callable)
    {
        # code...

        return $this->innerGroup->put($pattern, $callable);
    }

    public function any(string $pattern, callable | array $callable)
    {
        # code...

        return $this->innerGroup->any($pattern, $callable);
    }


    public function redirect(string $from, $to, int $status = 302)
    {
        return $this->innerGroup->redirect($from, $to, $status);
    }

    public function options(string $pattern, callable | array $callable)
    {
        # code...

        return $this->innerGroup->options($pattern, $callable);
    }



    public function addClosure(Closure $closure)
    {
        # code...

        $this->outerGroup->add($closure);

        return $this;
    }


    public function addMiddleware(MiddlewareInterface $singleMiddleware)
    {
        # code...

        $this->outerGroup->addMiddleware($singleMiddleware);

        return $this;
    }



    public function middleware(MiddlewareInterface ...$middleware)
    {
        # code...
        collect($middleware)->each(
            fn (MiddlewareInterface $singleMiddleware) =>
            $this->outerGroup->addMiddleware($singleMiddleware)
        );

        return $this;
    }

    public function closures(Closure ...$closures)
    {
        # code...


        collect($closures)->each(
            fn (Closure $closure) =>
            $this->outerGroup->add($closure)
        );


        return $this;
    }

    /**
     * Set the value of innerGroup
     *
     * @return  self
     */
    public function setInnerGroup(RouteCollectorProxy $innerGroup)
    {
        $this->innerGroup = $innerGroup;

        return $this;
    }

    /**
     * Set the value of outerGroup
     *
     * @return  self
     */
    public function setOuterGroup(RouteGroupInterface $outerGroup)
    {
        $this->outerGroup = $outerGroup;

        return $this;
    }
}
