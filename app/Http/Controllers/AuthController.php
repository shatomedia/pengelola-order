<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()){
            return redirect()->route('dashboard.index');
        }else{
            $title = 'Login';
            return view('auth.login', compact('title'));
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $auth = $request->only('email', 'password');

        if (Auth::attempt($auth)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // toast('Email/Kata Sandi yang Anda masukkan salah','error');
        return redirect()->back()->with('error', 'Login error');
    }

    public function logout(): RedirectResponse
    {
        try {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            // toast('Anda Berhasil Keluar', 'success');

            return redirect()->route('login.index');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            // toast('Anda Gagal Keluar', 'error');

            return back();
        }
    }
}
