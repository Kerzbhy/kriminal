<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\UserSession;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function login()
    {
        return view('auth/login');
    }

    // Proses login
    public function loginProses(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ], [
            'password.min' => 'Password Minimal 8 Karakter'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Hapus session token lama (jika ada)
            UserSession::where('user_id', $user->id)->delete();

            // Buat token baru
            $token = Str::random(60);
            UserSession::create([
                'user_id' => $user->id,
                'session_token' => $token,
            ]);

            // Simpan token di cookie (7 hari)
            $cookie = cookie('sync_token', $token, 60 * 24 * 7);

            return redirect()->route('dashboard')
                ->with('success', 'Anda Berhasil Login')
                ->withCookie($cookie);
        } else {
            return redirect()->back()->with('error', 'Username dan Password Salah');
        }
    }

    // Logout sinkron
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Hapus semua token login user → logout di semua browser
            UserSession::where('user_id', $user->id)->delete();
        }

        Auth::logout();

        return redirect()->route('login')
            ->with('success', 'Anda Berhasil Logout')
            ->withCookie(cookie()->forget('sync_token'));
    }
}
