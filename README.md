# Slim  Route Registry Library
&nbsp;
## Usage 
- The route registry library is made for slim it allows the user to create controllers that have attributes placed on them called resource controllers

    - Each resource Controller is a controller that uses index | show | delete | upsert | store . Or uses the Get | Put | Post | Patch | Delete attributes.

- You also have access to the original rest methods from Slim except the map method

- The main class is called the route registry class with three setup methods resources and resource

```php

    RouteRegistry::setup( App | RouteCollectorProxyInterface $app
    ):App | RouteCollectorProxyInterface 

    RouteRegistry::resource(string $path, string $class): void

    RouteRegistry::resources(
        ["path"=> string, "resources"=> string] 
        $array_of_resource_options): void
```
&nbsp;
#### Setup
    - The setup method takes in the app or a route group collector proxy.
    - It should be used before resource and resources. 
&nbsp;
#### Resource
    - It takes in a path string and a class to use as a string
    
    - You must use the class name as the second parameter

    - This method returns nothing to register middleware use middleware as attributes on a method or use the UseMiddlewareOn on and UseMiddlewareExceptForAttributes 
&nbsp;
#### Resources
    - It takes in an array of paths and resources
    - The resource method will be called on all of the paths and resources passed through


## Attributes

    - Attributes are the bread and butter of your app

    - There are two types of Attributes that are provided to you RouteMethod and UseMiddleware attributes

    - You can use middleware as attributes 
        
        - If you do they get registered as middleware
        - Each middleware must use the Psr\Http\Server\MiddlewareInterface or else it won't work   


### RouteMethodAttributes

- RouteMethodAttributes can only be used on methods

-  When one is applied to a method it will register it to a group using the path as the relative path to the resource's path the name as the route name and the method as the http method for the method to connect to.

- They take a path name and method
    
    - path : an absolute path string 
    - name : the name of the route 
    - method : the name of an http method you are going to connect the method to
    
&nbsp;
- RouteMethod
    
    ```php
        RouteMethod(path:string, name:string, method:string)
    
    ```
    - This attribute does everything mentioned in the route attributes section 

&nbsp;
- RouteMethod
    
    ```php
        RouteMethod(path:string, name:string, method:string)
    
    ```
    - This attribute does everything mentioned in the route attributes section 

&nbsp;
- RouteMethod
    
    ```php
        RouteMethod(path:string, name:string, method:string)
    
    ```
    - This attribute does everything mentioned in the route attributes section 

&nbsp;
- Get
    
    ```php
        Get(path:string, name:string,)
    
    ```
    - This attribute registers a get http method using the path and name   

&nbsp;
          
- Post
    
    ```php
        Post(path:string, name:string,)
    
    ```
    - This attribute registers a post http method using the path and name   

&nbsp;
     
- Patch
    
    ```php
        Patch(path:string, name:string,)
    
    ```
    - This attribute registers a patch http method using the path and name   

&nbsp;
     
- Put
    
    ```php
        Put(path:string, name:string,)
    
    ```
    - This attribute registers a put http method using the path and name   

&nbsp;

- Delete
    
    ```php
        Delete(path:string, name:string,)
    
    ```
    - This attribute registers a delete http method using the path and name   

&nbsp;
     

