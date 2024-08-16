<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'nickname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validatedData['password'] = bcrypt($request->post('password'));

        $user = User::create($validatedData);

        return redirect('/login');
    }


    public function auth(Request $request)
    {
        $request->validate([
            'username' => 'required|min:5',
            'password' => 'required|min:5',
        ]);

        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/home');
        }

        return redirect()->back()->withErrors([
            'message' => "Username or Password doesn't match our records",
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

}
