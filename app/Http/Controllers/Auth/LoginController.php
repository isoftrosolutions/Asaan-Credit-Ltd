<?php
namespace App\Http\Controllers\Auth;

use App\Core\Auth;
use App\Core\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function create()
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        return $this->render('auth.login');
    }

    public function store()
    {
        $data = Request::validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $attempted = Auth::attempt($data['email'], $data['password']);

        if (!$attempted) {
            $user = \App\Models\User::findByEmail($data['email']);
            if ($user && $user->is_suspended) {
                flash('error', 'Your account has been suspended. Contact support for assistance.');
            } else {
                flash('error', 'Invalid email or password.');
            }
            $this->back();
        }

        $user = Auth::user();
        flash('success', 'Welcome back, ' . ($user->name ?? $user->company_name) . '!');

        if ($user->is_admin) {
            $this->redirect('/admin');
        }

        $dashboardRoute = $user->role === 'entrepreneur' ? '/dashboard/entrepreneur' : '/dashboard/investor';
        $this->redirect($dashboardRoute);
    }

    public function destroy()
    {
        Auth::logout();
        flash('info', 'You have been logged out.');
        $this->redirect('/');
    }
}
