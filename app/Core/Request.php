<?php
namespace App\Core;

class Request
{
    public static function input($key, $default = null) {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public static function all(): array {
        return array_merge($_GET, $_POST);
    }

    public static function only(array $keys): array {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = self::input($key);
        }
        return $data;
    }

    public static function has($key): bool {
        return isset($_POST[$key]) || isset($_GET[$key]);
    }

    public static function file($key): ?array {
        return $_FILES[$key] ?? null;
    }

    public static function method(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function isGet(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public static function validate(array $rules): array {
        $errors = [];
        $data = self::all();
        
        foreach ($rules as $field => $ruleSet) {
            $value = $data[$field] ?? '';
            $rules = explode('|', $ruleSet);
            
            foreach ($rules as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $errors[$field][] = "{$field} is required";
                }
                if (str_starts_with($rule, 'min:') && strlen($value) < (int)substr($rule, 4)) {
                    $errors[$field][] = "{$field} must be at least " . substr($rule, 4) . " characters";
                }
                if (str_starts_with($rule, 'max:') && strlen($value) > (int)substr($rule, 4)) {
                    $errors[$field][] = "{$field} must not exceed " . substr($rule, 4) . " characters";
                }
                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "{$field} must be a valid email";
                }
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $data;
            back();
        }
        
        return $data;
    }

    public static function errors(): array {
        $errors = $_SESSION['_errors'] ?? [];
        unset($_SESSION['_errors']);
        return $errors;
    }

    public static function hasErrors(): bool {
        return !empty($_SESSION['_errors']);
    }
}
