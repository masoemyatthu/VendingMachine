<?php

    require_once __DIR__ . '/../app/libraries/Router.php';
    require_once __DIR__ . '/../app/controllers/ApiController.php';

    $router = new Router($pdo);

    // Automatically register controller routes
    $router->registerControllerRoutes('UserController');
    $router->registerControllerRoutes('ProductsController');
    $router->registerControllerRoutes('ApiController');
    $router->registerControllerRoutes('TransactionController');

    // Dispatch request
    $router->dispatch(
        $_SERVER['REQUEST_URI'],
        $_SERVER['REQUEST_METHOD']
    );

?>