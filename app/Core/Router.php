<?php
namespace App\Core;

class Router
{
    private array $routes = [];
    private array $groupMiddleware = [];
    private string $prefix = '';

    public function get($uri, $handler, $middleware = []) {
        $this->addRoute('GET', $uri, $handler, $middleware);
    }

    public function post($uri, $handler, $middleware = []) {
        $this->addRoute('POST', $uri, $handler, $middleware);
    }

    public function put($uri, $handler, $middleware = []) {
        $this->addRoute('POST', $uri, $handler, $middleware, true);
    }

    public function delete($uri, $handler, $middleware = []) {
        $this->addRoute('POST', $uri, $handler, $middleware, true, 'DELETE');
    }

    public function group(array $attributes, \Closure $callback) {
        $previousPrefix = $this->prefix;
        $previousMiddleware = $this->groupMiddleware;
        
        if (isset($attributes['prefix'])) {
            $this->prefix .= '/' . trim($attributes['prefix'], '/');
        }
        if (isset($attributes['middleware'])) {
            $this->groupMiddleware = array_merge($this->groupMiddleware, (array)$attributes['middleware']);
        }
        
        $callback($this);
        
        $this->prefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    private function addRoute($method, $uri, $handler, $middleware, $overrideMethod = false, $realMethod = null) {
        $uri = $this->prefix . '/' . trim($uri, '/');
        $uri = preg_replace('#/+#', '/', $uri);
        
        $middleware = array_merge($this->groupMiddleware, $middleware);
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $uri);
        $pattern = '#^' . $pattern . '$#';
        
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'pattern' => $pattern,
            'handler' => $handler,
            'middleware' => $middleware,
            'override_method' => $overrideMethod,
            'real_method' => $realMethod,
        ];
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';
        
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/' && $basePath !== '\\') {
            $uri = substr($uri, strlen($basePath));
            $uri = $uri ?: '/';
        }

        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {
            $routeMethod = $route['method'];
            
            if ($route['override_method'] && $route['real_method']) {
                $routeMethod = $route['real_method'];
            }

            if ($method !== $routeMethod) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                foreach ($route['middleware'] as $mw) {
                    $this->runMiddleware($mw);
                }

                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (is_array($route['handler'])) {
                    [$class, $method] = $route['handler'];
                    $controller = new $class();
                    csrf_verify();
                    echo $controller->$method(...$params);
                } elseif (is_callable($route['handler'])) {
                    csrf_verify();
                    echo call_user_func_array($route['handler'], $params);
                } elseif (is_string($route['handler'])) {
                    csrf_verify();
                    echo view($route['handler']);
                }
                return;
            }
        }

        http_response_code(404);
        echo view('errors.404');
    }

    private function runMiddleware($mw) {
        if (class_exists($mw)) {
            $instance = new $mw();
            $instance->handle();
        }
    }
}
