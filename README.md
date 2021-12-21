---
aliases: [slim-route-registry-library]
tags: [slim, route-registry]
note type: Main
---

# Slim Route Registry

## Usage

The slim route registry library is a library that uses controllers and the metadata that comes from them to automatically create route groups and setup middleware.
The controller is used as the model for how routes are created and what methods they use. This library is created for [slim](https://www.slimframework.com) php only.

### Installation

```php
composer require louiss0/slim-route-registry
```

### Setup

To setup the library you have to first create the slim app and pass it into the setup method.

```php
use Slim\Factory\AppFactory;
use Louiss0\SlimRouteRegistry\RouteRegstry

$app = AppFactory::create();

RouteRegistry::setup($app);

```

Doing this will setup all of the systems necessary to create controllers called **["Resource Controllers"](#resources)**

## Sections

- [Resources](#resources)
  - [Manual Resources](#manual-resources)
  - [[Automatic Resources]](#automatic-resources)
- [Group Scoping](#group-Scoping)
- [Middleware](#middleware)
  - [Resource Middleware](#resource-middleware)
  - [Method Middleware](#method-middleware)
  - [Group Middleware](#group-middleware)

[## Resources](#sections)

A Resource Controller is a controller that either has a route method attribute attached to a method or uses a Automatic Registration Method. It's a controller with the necessary information needed for the Resource method to work. To create a resource you create a class then attach a Route Method Attribute to one of its methods.

- To register a resource you use the Route Registry resource method.

```php

RouteRegistry::resource(string $path, string $class_name);

```

- To register multiple resources use the resources method

```php

RouteRegistry::resource(array $resource_options);

```

### [Manual Resources](#sections)

A Manual Resource is a controller that is created by attaching route method attributes as to its methods. The route attributes will tell the `RouteMethodAttribute` is the main attribute all other attributes stem inherit from this attribute with a predefined http request method.

A route method attribute is an attribute that takes three parameters.

- path - The path of the route relative to the [[#Group Scoping|group scope]]
- name - The name of the route that will be created
- method - The http request **in lowercase** the method will respond to

> Note you don't need to use the Route Method Attribute directly you can
> use Attributes that are inherited from them instead.

```php

// will tell the resource method to register a http get request
#[Get(name, path)]
function fetchTheCars(){}

// will tell the resource method to register a http post request
#[Post(name, path)]
function loginAUser(){}

// will tell the resource method to register a http patch request
#[Patch(name, path)]
function changeTheCarInfo(){}

// will tell the resource method to register a http put request
#[Put(name, path)]
function putTheArticleInTheCollectionOrCreateANewOne(){}

// will tell the resource method to register a http delete request
#[Delete(name, path)]
function deleteThisPost(){}

```

### [Automatic Resources](#sections)

An Automatic Resource is a controller that uses a Automatic Registration Method.

An Automatic Registration Method is a method that has a name that will be used by the resource method to register a route based on it's name.

```php

	// Will be used as a plain get request handler
	function collect() {}

	// Will be used as a  get request handler with id passed as params
	function show() {}

	// Will be used as a plain post request handler
	function store() {}

	// Will be used as a  put request handler with id passed as params
	function upsert() {}

	// Will be used as a  patch request handler with id passed as params
	function update() {}

	// Will be used as a  delete request handler with id passed as params
	function destroy() {}


```

> Note: If you add a route method attribute to one of these methods you'll get an error

> Note: The id parameter must be an int

## [Group Scoping](#sections)

In most apps you want to have the power to wrap resources under a group that will be be used to either call middleware before any of their methods are called or controlling the path the user must use to access a resource. To do this you use the group method.

```php

RouteRegistry::group(string $path, Closure $closure);

```

- The path is the pattern that will be used in the group method
- The closure will be the function that will be called in the closure of the group method. When you use the resource method in the closure passed the resource path will be appended to the group path as usual

[## Middleware](#sections)

You can use middleware in your app by using Use Middleware Attributes on methods and controllers and controller methods. You can tell the resource method to create the same middleware to be called before a request handler is called or before multiple request handlers are called.

```php

#[UseMiddleware([TestMiddleware::class])]
class Controller {}


class Controller {


	#[UseMiddleware([TestMiddleware::class])]
	function getOrdersByName(){}

}


```

### [Resource Middleware](#sections)

To apply middleware to a route group created from the resource method you use the Use Middleware Attribute
on a controller.

```php

	// takes a string of middleware
	#[UseMiddleware(array $middleware)]
	class PostController {}

```

### [Method Middleware](#sections)

When it comes to putting middleware in a route you must use a class that implements the Middleware interface as a attribute or the following attributes on a controller:

```php

#[UseMiddlewareOn(array $method_names, array $middleware)]
	class AuthController {}


```

- The method names are the names of the handlers for the routes the middleware will be added to. The middleware will only go to those routes.

```php

#[UseMiddlewareExceptFor(array $method_names, array $middleware)]
	class PostController {}

```

- The method names are the names of the handlers for the routes the middleware will not be added to. The middleware go to every route but those routes.

> Note: The middleware will be applied in the following order.
>
> 1.  `UseMiddleware` **when used on a method**
> 2.  `UseMiddlewareOn`
> 3.  `UseMiddlewareExceptFor`

> Note: The `UseMiddlewareOn` and Use `UseMiddlewareExceptFor` attributes are repeatable

```php

#[
UseMiddlewareOn(["collect", "store"],[Test4Middleware::class])
UseMiddlewareExceptOn(["collect", "upsert"],[Test3Middleware::class])

]
class Controller {}

```

### [Group Middleware](#sections)

To add middleware to a group use the created by the `RouteRegistry::group()` method use

```php
RouteRegistry::groupMiddleware(MiddlewareInterface...$middleware);
```
