# Slim  Route Registry Library
<br>  

## Usage 

This library allows you to connect class methods to http routes and add middleware by adding attributes to a controller. 

This is done by using the resource method. It takes an absolute path as a string  and a class name as a second parameter.
This method creates a route group by using the path and extracts every attribute that was added to a class and its methods. 
Then creates an instance of each attribute and then uses them to either add a method to
a route or add middleware to each group.

- If a class method has a Route Method attribute attached to it it will be used as a GET | POST | PATCH | PUT | DELETE  method with the information passed to the parameters
   used to configure the route

- If a class method has a Route Method attribute attached to it and an attribute that implements the ` Psr\Http\Server\MiddlewareInterface ` on it a that method will be registered to route using that attribute and middleware will be added to that route 

- If a middleware was added to a class that middleware will be added to the route group generated by resource method

- The use middleware attributes can be used to control how middleware is added   [Use Middleware Attributes Section](#use-middleware-attributes)  

- The resource method will auto register methods on a controller if they have certian names and even allow you to pass id's as a paramter to those methods 
 [Automatic Registration Methods](#automatic-registration-methods)          


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

    RouteRegistry::setup( App | RouteCollectorProxyInterface $app)
    
    RouteRegistry::group(string $path, Closure $callback);

    RouteRegistry::resource(string $path, string $class): void

    RouteRegistry::resources(
        ["path"=> string, "resources"=> string] 
        ...$array_of_resource_options): void
```
<br>  

#### [Setup](#sections)
    
    - The setup method takes in the app or a route group collector proxy.
    - It sets up up the app or route collector proxy to create resources.  
    - It should be used before resource and resources.
    
    ```php 
            
            $app = AppFactory::create();
            
            RouteRegistry::setup($app);          
    ```
    
<br>  

#### [Resource](#sections)

    - It takes in a path string and a class to use as a string
    - You must use the class name as the second parameter
    - Creates a route group by using the path and extracts every attribute that was added to a class and its methods. 
      Then creates an instance of each attribute and then uses them to either add a method to a route or add middleware to each group.

     
    ```php                         
        RouteRegistry::resource("/posts", PostController::class);
    ```
     
     
 
<br>  

#### [Resources](#sections)
 
    - It takes in any number of paths and resources
    - The resource method will be called on all of the paths and resources passed through
   
```php                         
    RouteRegistry::resources(
    ["path"=> "/posts", "resource"=> PostController::class]
    );
```

#### [Group](#sections)

- It takes in a path first and a closure second

- If you used slim without this library you should expect to get a group as the parameter that is passed in to the function. But that is not the case, because the setup function will be called and it will be passed the route collector proxy 

- That means that when you call any methods from within the callback function of the route group they will be scoped to that route.


   ```php
   
      RouteRegistry::group("/api", function (){
      
      
               RouteRegistry::resource("/posts", PostController::class);
      });
   
   ```


## [Attributes](#sections)

    - Attributes are the bread and butter of your app

    - There are two types of Attributes that are provided to you RouteMethod and UseMiddleware attributes

    

### [Route Method Attributes](#sections)

- RouteMethodAttributes can only be used on methods

-  When one is applied to a method. RouteRegistry will use it as a handler. The path is the path that method will be concatenated  to the resource methods path. The name is the of the newly created route. The method is the Http request you want method to handle. 

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
        
        
        #[Get(path:string, name:string,)]
        function example(){}
    
    ```
    - This attribute will tell `RouteRegistry` to register the method as a get request handler using the path and name   

<br>  

          
- #### [Post](#sections)
    
    ```php
        #[Post(path:string, name:string,)]
        function example(){}
    
    ```
    - This attribute will tell `RouteRegistry` to register the method as a post request handler using the path and name   

<br>  

     
- #### [Patch](#sections)
    
    ```php
        #[Patch(path:string, name:string,)]
        function example(){}

    ```
    - This attribute will tell `RouteRegistry` to register the method as a patch request handler using the path and name   

<br>  

     
- #### [Put](#sections)
    
    ```php
        #[Put(path:string, name:string,)]
        function example(){}
    ```
    - This attribute will tell `RouteRegistry` to register the method as a put request handler using the path and name   

<br>  


- #### [Delete](#sections)
    
    ```php
        #[Delete(path:string, name:string,)]
        function example(){}

    ```
    - This attribute will tell `RouteRegistry` to register the method as a delete request handler using the path and name   

<br>  

### [Use Middleware Attributes](#sections)

- The use middleware attributes are each used to tell the resource method that the middleware it its second parameter must be registered after the method and it's middleware are in the method names parameter.

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

- These methods cannot be altered by the `RouteMethod` Attribute at all

- You can use dependency injection to get what you want or use contracts 

- The **group path** is the path used as the first argument to the resource method minus the  ` / `  

|Class Method| Http Method | Path | Route Name | 
|------|------|------ | ----- |
| index| Get  |  ""   | group path.index |
| store| Post | "" |group path.store |
| show | Get  | "/{id:\d+}"| group path.show |
| update| Patch  | "/{id:\d+}"|group path.update |
| upsert| Put  | ""|group path.upsert |
| destroy| Delete  | "/{id:\d+}"| group path.destroy |


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
