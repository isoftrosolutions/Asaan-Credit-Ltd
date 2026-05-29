<?php
namespace App\Core;

class Auth
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function user(): ?object
    {
        self::startSession();
        if (isset($_SESSION['user_id'])) {
            return Database::fetch("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
        }
        return null;
    }

    public static function id(): ?int
    {
        self::startSession();
        return $_SESSION['user_id'] ?? null;
    }

    public static function check(): bool
    {
        self::startSession();
        return isset($_SESSION['user_id']);
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = Database::fetch("SELECT * FROM users WHERE email = ?", [$email]);
        if ($user && password_verify($password, $user->password)) {
            self::startSession();
            $_SESSION['user_id'] = $user->id;
            session_regenerate_id(true);
            return true;
        }
        return false;
    }

    public static function login(int $userId): void
    {
        self::startSession();
        $_SESSION['user_id'] = $userId;
        session_regenerate_id(true);
    }

    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            $_SESSION['_redirect'] = $_SERVER['REQUEST_URI'];
            redirect(route('login'));
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        $user = self::user();
        if (!$user || !$user->is_admin) {
            abort(403, 'Unauthorized. Admin access only.');
        }
    }

    public static function requireVerified(): void
    {
        self::requireAuth();
        $user = self::user();
        if (!$user || $user->verification_status !== 'verified') {
            set_flash('error', 'Please complete your profile and get verified first.');
            redirect(route('profile.edit'));
            exit;
        }
    }
}
