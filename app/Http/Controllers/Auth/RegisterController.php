<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
        $data = \App\Core\Request::all();

        $errors = [];

        if (empty($data['name']) || strlen($data['name']) > 255) {
            $errors['name'][] = 'The name field is required and must not exceed 255 characters.';
        }

        $email = strtolower(trim($data['email'] ?? ''));
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = 'The email must be a valid email address.';
        }

        $existing = \App\Core\Database::fetch("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existing) {
            $errors['email'][] = 'The email has already been taken.';
        }

        $password = $data['password'] ?? '';
        if (strlen($password) < 8) {
            $errors['password'][] = 'The password must be at least 8 characters.';
        }
        if ($password !== ($data['password_confirmation'] ?? '')) {
            $errors['password'][] = 'The password confirmation does not match.';
        }

        if (empty($data['role']) || !in_array($data['role'], ['investor', 'entrepreneur'])) {
            $errors['role'][] = 'The role field is required.';
        }
        if (empty($data['account_type']) || !in_array($data['account_type'], ['individual', 'company'])) {
            $errors['account_type'][] = 'The account type field is required.';
        }
        if (!empty($data['phone']) && strlen($data['phone']) > 20) {
            $errors['phone'][] = 'The phone must not exceed 20 characters.';
        }

        if (!empty($errors)) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $data;
            back();
        }

        $userId = \App\Core\Database::insert('users', [
            'name' => $data['name'],
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $data['role'],
            'account_type' => $data['account_type'],
            'phone' => $data['phone'] ?? null,
            'verification_status' => 'unverified',
        ]);

        \App\Core\Auth::login($userId);

        redirect(route($data['role'] . '.dashboard'));
    }
}
