<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        if (session()->has('id_user')) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->with('error', 'Email atau password yang kamu masukkan salah.')
                ->withInput($request->only('email'));
        }

        if ($user->status !== 'active') {
            return back()->with('error', 'Akun kamu tidak aktif. Silakan hubungi administrator.');
        }

        session([
            'id_user' => $user->id,
            'username' => $user->email,
            'nama' => $user->name,
            'role' => $user->role,
        ]);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    public function logout()
    {
        session()->forget([
            'id_user',
            'username',
            'nama',
            'role',
        ]);

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Kamu berhasil logout.');
    }
}