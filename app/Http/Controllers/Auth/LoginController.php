<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store()
    {
        $email = \App\Core\Request::input('email');
        $password = \App\Core\Request::input('password');
        $remember = \App\Core\Request::boolean('remember');

        if (empty($email)) {
            $_SESSION['_errors'] = ['email' => ['The email field is required.']];
            $_SESSION['_old'] = ['email' => $email];
            back();
        }
        if (empty($password)) {
            $_SESSION['_errors'] = ['password' => ['The password field is required.']];
            $_SESSION['_old'] = ['email' => $email];
            back();
        }

        if (!\App\Core\Auth::attempt($email, $password)) {
            $_SESSION['_errors'] = ['email' => ['These credentials do not match our records.']];
            $_SESSION['_old'] = ['email' => $email];
            back();
        }

        session_regenerate_id(true);

        $user = \App\Core\Auth::user();
        if ($user->is_admin) {
            redirect(route('admin.dashboard'));
        }
        redirect(route($user->role . '.dashboard'));
    }

    public function destroy()
    {
        \App\Core\Auth::logout();
        redirect('/');
    }
}
