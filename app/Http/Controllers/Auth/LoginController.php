<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password tidak sesuai.'
                ])
                ->withInput($request->only('email'));
        }

        if ($user->status !== 'active') {
            return back()
                ->withErrors([
                    'email' => 'Akun kamu tidak aktif atau telah diblokir.'
                ]);
        }

        $request->session()->regenerate();

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

    public function logout(Request $request)
    {
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Kamu berhasil logout.');
    }
}