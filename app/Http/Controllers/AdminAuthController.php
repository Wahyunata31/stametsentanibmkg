<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin() {
        return view('admin.login');
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'captcha'  => 'required|captcha'
        ], ['captcha.captcha' => 'Kode Captcha salah!']);

        if (Auth::attempt(['name' => $request->username, 'password' => $request->password])) {
            return redirect()->route('admin.create');
        }

        return back()->with('error', 'Username atau password salah.');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
