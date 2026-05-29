<?php
namespace App\Core;

class Request
{
    public static function method(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST' && isset($_POST['_method'])) {
            return strtoupper($_POST['_method']);
        }
        return $method;
    }

    public static function path(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return rtrim($uri, '/') ?: '/';
    }

    public static function all(): array
    {
        $data = $_POST;
        if (self::isJson()) {
            $json = json_decode(file_get_contents('php://input'), true) ?? [];
            $data = array_merge($data, $json);
        }
        return $data;
    }

    public static function input(string $key, $default = null): mixed
    {
        $data = self::all();
        return $data[$key] ?? $default;
    }

    public static function file(string $key): ?array
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE ? $_FILES[$key] : null;
    }

    public static function hasFile(string $key): bool
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE;
    }

    public static function has(string $key): bool
    {
        return isset($_POST[$key]) || isset($_GET[$key]);
    }

    public static function filled(string $key): bool
    {
        return self::has($key) && !empty(self::input($key));
    }

    public static function boolean(string $key): bool
    {
        $val = self::input($key);
        return in_array($val, [true, 1, '1', 'true', 'on', 'yes'], true);
    }

    public static function query(string $key, $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public static function validate(array $rules): array
    {
        $data = self::all();
        $errors = [];
        $validated = [];

        foreach ($rules as $field => $ruleStr) {
            $rules = is_array($ruleStr) ? $ruleStr : explode('|', $ruleStr);
            $value = $data[$field] ?? null;

            foreach ($rules as $rule) {
                if ($rule === 'required' && ($value === null || $value === '')) {
                    $errors[$field][] = "The {$field} field is required.";
                    break;
                }
                if (str_starts_with($rule, 'max:')) {
                    $max = (int)substr($rule, 4);
                    if (is_string($value) && strlen($value) > $max) {
                        $errors[$field][] = "The {$field} must not exceed {$max} characters.";
                    }
                }
                if (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 3);
                    if (is_numeric($value) && (float)$value < $min) {
                        $errors[$field][] = "The {$field} must be at least {$min}.";
                    }
                }
                if ($rule === 'email' && $value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "The {$field} must be a valid email.";
                }
                if ($rule === 'url' && $value && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $errors[$field][] = "The {$field} must be a valid URL.";
                }
                if (str_starts_with($rule, 'in:')) {
                    $allowed = explode(',', substr($rule, 3));
                    if ($value && !in_array($value, $allowed)) {
                        $errors[$field][] = "The {$field} must be one of: " . implode(', ', $allowed) . ".";
                    }
                }
                if ($rule === 'boolean') {
                    if ($value !== null && !in_array((string)$value, ['1', '0', 'true', 'false', 'on', 'off'], true)) {
                        $errors[$field][] = "The {$field} must be true or false.";
                    }
                }
                if (str_starts_with($rule, 'numeric')) {
                    if ($value !== null && $value !== '' && !is_numeric($value)) {
                        $errors[$field][] = "The {$field} must be a number.";
                    }
                }
                if (str_starts_with($rule, 'integer')) {
                    if ($value !== null && $value !== '' && !ctype_digit((string)$value)) {
                        $errors[$field][] = "The {$field} must be an integer.";
                    }
                }
                if (str_starts_with($rule, 'array')) {
                    if ($value !== null && !is_array($value)) {
                        $errors[$field][] = "The {$field} must be an array.";
                    }
                }
            }

            if ($value !== null) {
                $validated[$field] = $value;
            }
        }

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $data;
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
            exit;
        }

        return $validated;
    }

    private static function isJson(): bool
    {
        return isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json');
    }
}
