# Slim  Route Registry Library
<br>  

## Usage 
- The route registry library is made for slim it allows the user to create controllers that have attributes placed on them called resource controllers

    - Each resource Controller is a controller that uses index | show | delete | upsert | store . Or uses the Get | Put | Post | Patch | Delete attributes.

- You also have access to the original rest methods from Slim except the map method

- The main class is called the route registry class with three methods setup resources and resource

<br>

## Sections

- [Route Registry Class](#route-registry-class)
    - [Setup](#setup)
    - [Resource](#resource)
    - [Group](#group)
    - [Resources](#resources)

- [Attributes](#attributes)
    
    - [Route Method Attributes](#route-method-attributes)
        
        - [RouteMethod](#routemethod)
        - [Get](#get)
        - [Post](#post)
        - [Patch](#patch)
        - [Put](#put)
        - [Delete](#delete)
        
    - [Use Middleware Attributes](#use-middleware-attributes)
        
        - [UseMiddlewareOn](#usemiddlewareon)
        - [UseMiddlewareExceptFor](#usemiddlewareexceptfor)


- [Automatic Registration Methods](#automatic-registration-methods) 

- [Contracts](#contracts)

    - [CRUD Controller Contract](#crud-controller-contract)

    - [Resource Controller Contract](#resource-controller-contract)

## [Route Registry Class](#sections)

```php

    RouteRegistry::setup( App | RouteCollectorProxyInterface $app
    ):App | RouteCollectorProxyInterface 


    RouteRegistry::group(string $path, Closure $callback);

    RouteRegistry::resource(string $path, string $class): void

    RouteRegistry::resources(
        ["path"=> string, "resources"=> string] 
        ...$array_of_resource_options): void
```
<br>  

#### [Setup](#sections)
    - The setup method takes in the app or a route group collector proxy.
    - It should be used before resource and resources. 
<br>  

#### [Resource](#sections)
    - It takes in a path string and a class to use as a string
    
    - You must use the class name as the second parameter

    - This method returns nothing to register middleware use middleware as attributes on a method or use the UseMiddlewareOn on and UseMiddlewareExceptForAttributes 
<br>  

#### [Resources](#sections)
    - It takes in any number of paths and resources
    - The resource method will be called on all of the paths and resources passed through

#### [Group](#sections)

- It takes in a path first and a closure second

- If you used slim without this library you should expect to get a group as the parameter that is passed in to the function. But that is not the case, because the setup function will be called and it will be passed the route collector proxy 

- That means that when you call any methods from within the callback function of the route group they will be scoped to that route.


## [Attributes](#sections)

    - Attributes are the bread and butter of your app

    - There are two types of Attributes that are provided to you RouteMethod and UseMiddleware attributes

    - You can use middleware as attributes 
        
        - If you do they get registered as middleware
        - Each middleware must use the Psr\Http\Server\MiddlewareInterface or else it won't work   


### [Route Method Attributes](#sections)

- RouteMethodAttributes can only be used on methods

-  When one is applied to a method it will register it to a group using the path as the relative path to the resource's path the name as the route name and the method as the http method for the method to connect to.

- They take a path name and method
    
    - path 
        : an absolute path string 
    - name 
        : the name of the route 
    - method 
        : the name of an http method you are going to connect the method to
    
<br>  

- #### [RouteMethod](#sections)
    
    ```php
        RouteMethod(path:string, name:string, method:string)
    
    ```
    - This attribute does everything mentioned in the route attributes section 

<br>  


- #### [Get](#sections)
    
    ```php
        Get(path:string, name:string,)
    
    ```
    - This attribute registers a get http method using the path and name   

<br>  

          
- #### [Post](#sections)
    
    ```php
        Post(path:string, name:string,)
    
    ```
    - This attribute registers a post http method using the path and name   

<br>  

     
- #### [Patch](#sections)
    
    ```php
        Patch(path:string, name:string,)
    
    ```
    - This attribute registers a patch http method using the path and name   

<br>  

     
- #### [Put](#sections)
    
    ```php
        Put(path:string, name:string,)
    
    ```
    - This attribute registers a put http method using the path and name   

<br>  


- #### [Delete](#sections)
    
    ```php
        Delete(path:string, name:string,)
    
    ```
    - This attribute registers a delete http method using the path and name   

<br>  

### [Use Middleware Attributes](#sections)

- The use middleware attributes are each used to tell the controller what methods to place middleware in front of.

- They must be put on top of the class to work

    - ```php

            #[UseMiddlewareOn(method_names:["delete"] , middleware:[AuthMiddleware::class] )]
                class UserController {}
      ```
- These attributes can be repeated multiple times

- The middleware in these attributes will be registered after all the middleware attributes that were used on each method 

- They each take two parameters 

    - method_names 
        : The name of the methods you want to use 
    
    - middleware 
        : The array of middleware that are going to be used 
            - ! they must be passed as strings
<br>  

- #### [UseMiddlewareOn](#sections)

    ```php
    UseMiddlewareOn(method_names: string[], middleware:string[])
    
    ```
    - This Attribute will tell the resource method to
    register middleware to all the methods with the name specified in the method names parameter  

<br>  

- #### [UseMiddlewareExceptFor](#sections)

    ```php
    UseMiddlewareExceptFor(method_names: string[], middleware:string[])
    
    ```
    - This Attribute will tell the resource method to
    register middleware to all the methods with the name not specified in the method names parameter  

<br>  


- #### [Remember These Rules](#sections) 

    1. The middleware added to the class itself will be registered after the ones on each Method

    1. The middleware attributes on a class will be registered before the ones applied using the UseMiddlewareAttributes

    1. The UseMiddlewareExceptForAttribute will be the last one to register any middleware 

<br>

### [Automatic Registration Methods](#sections)


- When you use a controller you can create six methods that automatically get registered to a http method When registered as a resource Controller

- These methods cannot be altered by the RouteGroupMethodAttribute at all

- You can use dependency injection to get what you want or use contracts 

- The **group path** is the path used as the first argument to the resource method minus the  ` / `  

|Class Method| Http Method | Path | Route Name | 
|------|------|------ | ----- |
| index| Get  |  ""   | {{group path}}.index|
| store| Post | "" |{{group path}}.store
| show | Get  | "/{id:\d+}"| {{group path}}.show|
| update| Patch  | "/{id:\d+}"|{{group path}}.update|
| upsert| Put  | ""|{{group path}}.upsert|
| destroy| Delete  | "/{id:\d+}"| {{group path}}.destroy


### [Contracts](#sections)

- Contracts are just interfaces that allow you to apply the Automatic Registration Methods in a conventional way . That means you don't have to use them

#### [CRUD Controller Contract](#sections)
```php

interface CRUDControllerContract
{

    const INDEX = "index";
    const SHOW = "show";
    const STORE = "store";
    const DESTROY = "destroy";
    const UPDATE = "update";

    public function index(ServerRequest $request, Response $response): Response;
    public function show(int $id, Response $response): Response;
    public function store(ServerRequest $request, Response $response): Response;
    public function update(int $id, Response $response): Response;
    public function destroy(int $id, Response $response): Response;
}

```


#### [Resource Controller Contract](#sections)

```php
interface ResourceControllerContract extends CRUDControllerContract
{

    public const UPSERT = "upsert";
    public function upsert(ServerRequest $request, Response $response): Response;
}
```