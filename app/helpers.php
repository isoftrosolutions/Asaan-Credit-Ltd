<?php
function env(string $key, $default = null): mixed
{
    static $env = null;
    if ($env === null) {
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), '#')) continue;
                if (str_contains($line, '=')) {
                    [$k, $v] = explode('=', $line, 2);
                    $env[trim($k)] = trim($v);
                }
            }
        }
    }
    return $env[$key] ?? $default;
}

function view(string $name, array $data = []): string
{
    extract($data);
    ob_start();
    $path = __DIR__ . '/../resources/views/' . str_replace('.', '/', $name) . '.php';
    if (file_exists($path)) {
        require $path;
    } else {
        $bladePath = __DIR__ . '/../resources/views/' . str_replace('.', '/', $name) . '.blade.php';
        if (file_exists($bladePath)) {
            require $bladePath;
        } else {
            throw new \RuntimeException("View [{$name}] not found.");
        }
    }
    return ob_get_clean();
}

function render(string $name, array $data = []): void
{
    echo view($name, $data);
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function back(): void
{
    redirect($_SERVER['HTTP_REFERER'] ?? '/');
}

function route(string $name, array $params = []): string
{
    return \App\Core\Router::namedRoute($name, $params);
}

function abort(int $code, string $message = ''): void
{
    http_response_code($code);
    if ($message) {
        echo $message;
    }
    exit;
}

function set_flash(string $key, string $value): void
{
    \App\Core\Auth::startSession();
    $_SESSION['_flash'][$key] = $value;
}

function flash(string $key): ?string
{
    \App\Core\Auth::startSession();
    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

function has_flash(string $key): bool
{
    \App\Core\Auth::startSession();
    return isset($_SESSION['_flash'][$key]);
}

function old(string $key, $default = ''): string
{
    \App\Core\Auth::startSession();
    $value = $_SESSION['_old'][$key] ?? $default;
    return is_array($value) ? '' : htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function error(string $field): ?string
{
    \App\Core\Auth::startSession();
    $errors = $_SESSION['_errors'] ?? [];
    $msg = $errors[$field][0] ?? null;
    return $msg ? htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') : null;
}

function has_error(string $field): bool
{
    \App\Core\Auth::startSession();
    return isset($_SESSION['_errors'][$field]);
}

function clear_old(): void
{
    unset($_SESSION['_errors'], $_SESSION['_old']);
}

function csrf_field(): string
{
    \App\Core\Auth::startSession();
    if (empty($_SESSION['_token'])) {
        $_SESSION['_token'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="_token" value="' . $_SESSION['_token'] . '">';
}

function method_field(string $method): string
{
    return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
}

function csrf_token(): string
{
    \App\Core\Auth::startSession();
    if (empty($_SESSION['_token'])) {
        $_SESSION['_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_token'];
}

function verify_csrf(): void
{
    \App\Core\Auth::startSession();
    $token = $_POST['_token'] ?? '';
    if (empty($_SESSION['_token']) || !hash_equals($_SESSION['_token'], $token)) {
        abort(419, 'Page expired.');
    }
}

function upload_file(array $file, string $directory): string|false
{
    $config = require __DIR__ . '/../config/app.php';
    $uploadPath = $config['storage_path'] . '/app/public/' . $directory;
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }
    $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
    $dest = $uploadPath . '/' . $filename;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return $directory . '/' . $filename;
    }
    return false;
}

function storage_path(string $path = ''): string
{
    $config = require __DIR__ . '/../config/app.php';
    return rtrim($config['storage_path'], '/') . ($path ? '/' . ltrim($path, '/') : '');
}

function str_slug(string $text): string
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    return strtolower($text);
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

function today(): string
{
    return date('Y-m-d');
}

function auth(): \App\Core\Auth
{
    return new class {
        public function user() { return \App\Core\Auth::user(); }
        public function id() { return \App\Core\Auth::id(); }
        public function check() { return \App\Core\Auth::check(); }
        public function login(int $userId) { \App\Core\Auth::login($userId); }
        public function logout() { \App\Core\Auth::logout(); }
        public function attempt(string $email, string $password) { return \App\Core\Auth::attempt($email, $password); }
    };
}

function redirect_response(string $url): void
{
    redirect($url);
}

function json_response(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
