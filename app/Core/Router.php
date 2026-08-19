<?php
namespace App\Core;

class Router {
    protected $routes = [];

    public function get($uri, $controllerAction, $middlewares = []) {
        $this->addRoute('GET', $uri, $controllerAction, $middlewares);
    }

    public function post($uri, $controllerAction, $middlewares = []) {
        $this->addRoute('POST', $uri, $controllerAction, $middlewares);
    }

    protected function addRoute($method, $uri, $controllerAction, $middlewares) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $controllerAction,
            'middlewares' => (array) $middlewares
        ];
    }

    public function dispatch($uri, $method) {
        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\:([a-zA-Z0-9_]+)/', '(?P<$1>[a-zA-Z0-9_-]+)', $route['uri']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches) && $route['method'] === $method) {
                
                // Process Middlewares
                foreach ($route['middlewares'] as $middleware) {
                    Middleware::resolve($middleware);
                }

                // CSRF Protection for POST requests
                if ($method === 'POST') {
                    if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
                        http_response_code(403);
                        die('CSRF token mismatch or expired.');
                    }
                }

                $parts = explode('@', $route['action']);
                $controller = "App\\Controllers\\" . $parts[0];
                $action = $parts[1];

                // Filter matches for named parameters only
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (class_exists($controller)) {
                    $controllerInstance = new $controller();
                    if (method_exists($controllerInstance, $action)) {
                        return call_user_func_array([$controllerInstance, $action], array_values($params));
                    }
                }
            }
        }
        
        http_response_code(404);
        echo "404 Not Found";
    }
}
