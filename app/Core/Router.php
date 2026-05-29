<?php
namespace App\Core;

class Router
{
    private static array $routes = [];
    private static array $globalMiddleware = [];
    private static string $prefix = '';
    private static array $groupMiddleware = [];
    private static array $namedRoutes = [];

    public static function get(string $uri, callable|array $handler, ?string $name = null): void
    {
        self::addRoute('GET', $uri, $handler, $name);
    }

    public static function post(string $uri, callable|array $handler, ?string $name = null): void
    {
        self::addRoute('POST', $uri, $handler, $name);
    }

    public static function put(string $uri, callable|array $handler, ?string $name = null): void
    {
        self::addRoute('PUT', $uri, $handler, $name);
    }

    public static function view(string $uri, string $view, ?string $name = null): void
    {
        self::addRoute('GET', $uri, function () use ($view) {
            $args = func_get_args();
            render($view);
        }, $name);
    }

    private static function addRoute(string $method, string $uri, callable|array $handler, ?string $name): void
    {
        $fullUri = self::$prefix . $uri;
        $route = [
            'method' => $method,
            'uri' => $fullUri,
            'handler' => $handler,
            'middleware' => self::$groupMiddleware,
        ];
        self::$routes[] = $route;
        if ($name) {
            self::$namedRoutes[$name] = $fullUri;
        }
    }

    public static function group(array $attributes, callable $callback): void
    {
        $previousPrefix = self::$prefix;
        $previousMiddleware = self::$groupMiddleware;

        if (isset($attributes['prefix'])) {
            self::$prefix .= '/' . trim($attributes['prefix'], '/');
            self::$prefix = rtrim(self::$prefix, '/');
        }
        if (isset($attributes['middleware'])) {
            $mw = is_array($attributes['middleware']) ? $attributes['middleware'] : [$attributes['middleware']];
            self::$groupMiddleware = array_merge(self::$groupMiddleware, $mw);
        }

        $callback();

        self::$prefix = $previousPrefix;
        self::$groupMiddleware = $previousMiddleware;
    }

    public static function middleware(string $name): self
    {
        self::$globalMiddleware[] = $name;
        return new static;
    }

    public static function dispatch(): void
    {
        $method = Request::method();
        $uri = Request::path();

        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) continue;

            $params = self::matchRoute($route['uri'], $uri);
            if ($params !== false) {

                foreach ($route['middleware'] as $mw) {
                    self::handleMiddleware($mw);
                }

                if (is_array($route['handler'])) {
                    [$class, $action] = $route['handler'];
                    $controller = new $class();

                    $ref = new \ReflectionMethod($controller, $action);
                    $args = [];
                    foreach ($ref->getParameters() as $param) {
                        $name = $param->getName();
                        if (isset($params[$name])) {
                            $args[] = $params[$name];
                        } elseif ($param->getType() && $param->getType()->getName() === 'App\Core\Request') {
                            $args[] = new Request();
                        } elseif ($param->isDefaultValueAvailable()) {
                            $args[] = $param->getDefaultValue();
                        } else {
                            $args[] = null;
                        }
                    }
                    $response = $ref->invokeArgs($controller, $args);
                } else {
                    $response = call_user_func_array($route['handler'], $params);
                }

                if (is_string($response)) {
                    echo $response;
                }
                return;
            }
        }

        http_response_code(404);
        render('errors.404');
    }

    private static function matchRoute(string $routeUri, string $requestUri): array|false
    {
        $routeUri = rtrim($routeUri, '/') ?: '/';

        if (str_contains($routeUri, '{')) {
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $routeUri);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $requestUri, $matches)) {
                return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            }
        } elseif ($routeUri === $requestUri) {
            return [];
        }

        return false;
    }

    public static function namedRoute(string $name, array $params = []): string
    {
        $uri = self::$namedRoutes[$name] ?? '/';
        foreach ($params as $key => $value) {
            $uri = str_replace('{' . $key . '}', $value, $uri);
        }
        return $uri;
    }

    private static function handleMiddleware(string $mw): void
    {
        if ($mw === 'auth') {
            Auth::requireAuth();
        } elseif ($mw === 'admin') {
            Auth::requireAdmin();
        } elseif ($mw === 'im.verified') {
            Auth::requireVerified();
        } elseif ($mw === 'guest') {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->is_admin) {
                    redirect(route('admin.dashboard'));
                } else {
                    redirect(route($user->role . '.dashboard'));
                }
                exit;
            }
        }
    }
}
