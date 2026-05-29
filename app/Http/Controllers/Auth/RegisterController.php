<?php
namespace App\Http\Controllers\Auth;

use App\Core\Auth;
use App\Core\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class RegisterController extends Controller
{
    public function create()
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        return $this->render('auth.register');
    }

    public function store()
    {
        $data = Request::validate([
            'name' => 'required|min:2|max:100',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role' => 'required',
        ]);

        $allowedRoles = ['investor', 'entrepreneur', 'franchisor', 'advisor'];
        if (!in_array($data['role'], $allowedRoles)) {
            flash('error', 'Invalid role selected.');
            $this->back();
        }

        if (User::findByEmail($data['email'])) {
            flash('error', 'An account with this email already exists.');
            $this->back();
        }

        $userId = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'account_type' => Request::input('account_type', 'individual'),
            'province' => Request::input('province', ''),
            'district' => Request::input('district', ''),
            'phone' => Request::input('phone', ''),
            'verification_status' => 'pending',
        ]);

        Auth::attempt($data['email'], $data['password']);

        flash('success', 'Account created successfully! Please complete your profile.');
        $this->redirect('/profile/edit');
    }
}
