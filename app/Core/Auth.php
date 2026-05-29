<?php
namespace App\Core;

class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $user = Database::fetch('SELECT * FROM users WHERE email = :email', ['email' => $email]);
        
        if ($user && password_verify($password, $user->password)) {
            if ($user->is_suspended) {
                return false;
            }
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_role'] = $user->role;
            return true;
        }
        
        return false;
    }

    public static function user(): ?object
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        return Database::fetch('SELECT * FROM users WHERE id = :id', [
            'id' => $_SESSION['user_id']
        ]);
    }

    public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function guest(): bool
    {
        return !self::check();
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_role']);
        session_destroy();
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && $user->is_admin;
    }

    public static function isVerified(): bool
    {
        $user = self::user();
        return $user && $user->verification_status === 'verified';
    }

    public static function requireAuth(): void
    {
        if (self::guest()) {
            flash('error', 'Please log in first.');
            redirect('/login');
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        if (!self::isAdmin()) {
            flash('error', 'Unauthorized access.');
            redirect('/');
        }
    }

    public static function requireVerified(): void
    {
        self::requireAuth();
        if (!self::isVerified()) {
            flash('error', 'Please complete verification first.');
            back();
        }
    }

    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
