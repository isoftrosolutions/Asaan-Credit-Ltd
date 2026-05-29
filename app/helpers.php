<?php
if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('view')) {
    function view($name, $data = []) {
        extract($data);
        $path = __DIR__ . '/../resources/views/' . str_replace('.', '/', $name) . '.php';
        if (!file_exists($path)) {
            throw new Exception("View [{$name}] not found at: {$path}");
        }
        ob_start();
        require $path;
        return ob_get_clean();
    }
}

if (!function_exists('render')) {
    function render($name, $data = []) {
        echo view($name, $data);
    }
}

if (!function_exists('redirect')) {
    function redirect($path) {
        header('Location: ' . $path);
        exit;
    }
}

if (!function_exists('back')) {
    function back() {
        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="_csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('csrf_verify')) {
    function csrf_verify() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_csrf_token'] ?? '';
            if (empty($token) || !hash_equals($_SESSION['_csrf_token'] ?? '', $token)) {
                die('CSRF token mismatch.');
            }
        }
    }
}

if (!function_exists('old')) {
    function old($key, $default = '') {
        return $_SESSION['_old'][$key] ?? $default;
    }
}

if (!function_exists('flash')) {
    function flash($key, $value = null) {
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return;
        }
        $val = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $val;
    }
}

if (!function_exists('has_flash')) {
    function has_flash($key) {
        return isset($_SESSION['_flash'][$key]);
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('upload_file')) {
    function upload_file($file, $directory = 'uploads') {
        $storagePath = __DIR__ . '/../storage/app/' . $directory;
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
        move_uploaded_file($file['tmp_name'], $storagePath . '/' . $filename);
        return $directory . '/' . $filename;
    }
}

if (!function_exists('storage_url')) {
    function storage_url($path) {
        if (empty($path)) return '';
        return '/storage/' . $path;
    }
}

if (!function_exists('route')) {
    function route($name, $params = []) {
        return '/' . $name;
    }
}

if (!function_exists('auth')) {
    function auth() {
        return App\Core\Auth::user();
    }
}

if (!function_exists('config')) {
    function config($key, $default = null) {
        $parts = explode('.', $key);
        $file = array_shift($parts);
        $path = __DIR__ . '/../config/' . $file . '.php';
        if (!file_exists($path)) return $default;
        $config = require $path;
        foreach ($parts as $part) {
            if (!isset($config[$part])) return $default;
            $config = $config[$part];
        }
        return $config;
    }
}
