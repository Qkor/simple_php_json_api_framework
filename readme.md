This is a small json API framework including routing, input validation, 
simple folder structure and abstract classes for controllers, 
services and entities to inherit from. 

Public methods of controllers in 'MF\Controller' namespace become routes. 
E.g. userController->register() becomes route /user/register

There is a simple user registration implemented just to show how the framework works. 