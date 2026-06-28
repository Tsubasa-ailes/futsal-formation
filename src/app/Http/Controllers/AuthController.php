<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'login_id' => ['required', 'string', 'max:255', 'unique:users,login_id'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^[a-zA-Z0-9]+$/'],
        ]);

        $user = User::create($validated);

        Auth::login($user);

        return redirect()->route('lineups.index');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login_id' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('lineups.index');
        }

        return back()->withErrors([
            'login_id' => 'ログインIDまたはパスワードが正しくありません。',
        ])->onlyInput('login_id');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
