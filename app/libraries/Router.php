<?php

    class Router
    {
        private $routes = [];
        private $pdo;

        public function __construct($pdo)
        {
            $this->pdo = $pdo;
        }

        public function get($uri, $action)
        {
            $this->routes[] = [
                'method' => 'GET',
                'uri' => $uri,
                'action' => $action
            ];
        }

        public function post($uri, $action)
        {
            $this->routes[] = [
                'method' => 'POST',
                'uri' => $uri,
                'action' => $action
            ];
        }

        public function dispatch($uri, $method)
        {
            // Get URL path
            $uri = parse_url($uri, PHP_URL_PATH);

            // Remove project base path
            $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

            if ($basePath !== '' && strpos($uri, $basePath) === 0) {
                $uri = substr($uri, strlen($basePath));
            }

            if ($uri === '') {
                $uri = '/';
            }

            // Match routes
            foreach ($this->routes as $route) {

                if ($route['method'] !== strtoupper($method)) {
                    continue;
                }

                $pattern = preg_replace_callback(
                    '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
                    function ($matches) {
                        return '([^/]+)';
                    },
                    $route['uri']
                );

                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $uri, $matches)) {

                    array_shift($matches);

                    [$controllerName, $methodName] =
                        explode('@', $route['action']);

                    require_once __DIR__ . '/../controllers/' . $controllerName . '.php';

                    $controller = new $controllerName($this->pdo);

                    return $controller->$methodName(...$matches);
                }
            }

            http_response_code(404);
            echo '404 - Page Not Found';
        }

        public function registerControllerRoutes($controllerName)
        {

            require_once __DIR__ . '/../libraries/Route.php';

            require_once __DIR__ . '/../controllers/' . $controllerName . '.php';

            $reflection = new ReflectionClass($controllerName);

            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {

                $attributes = $method->getAttributes(Route::class);

                foreach ($attributes as $attribute) {

                    $route = $attribute->newInstance();

                    $this->routes[] = [

                        'method' => strtoupper($route->method),

                        'uri' => $route->uri,

                        'action' => $controllerName . '@' . $method->getName()

                    ];

                }

            }

        }

    }

?>