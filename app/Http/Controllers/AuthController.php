<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            ActivityLog::log("Melakukan login ke sistem");
            return redirect()->intended('/admin/dashboard')->with('welcome', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->with('loginError', 'Login Gagal! Username atau password salah.');
    }

    public function logout() {
        ActivityLog::log("Melakukan logout dari sistem");
        Auth::logout();
        return redirect('/');
    }
}