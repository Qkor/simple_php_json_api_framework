This is a small json API framework including routing, input validation, 
simple folder structure, abstract classes for controllers, 
services and entities to inherit from and simple authentication.

### Routing

If 'autoRoutes' in config is set to true, public methods of controllers become routes. 
E.g. userController->register() becomes route /user/register

Setup $routes array in controllers to create routes for controller methods.

### Input validation

Invoke $this->validateInput() in controllers to validate data passed by json and query parameters. 
Only validated parameters can be accessed in controllers. On validation failure, API returns error response.
