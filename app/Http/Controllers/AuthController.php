<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'pelanggan', // Set role default ke pelanggan
        ]);

        // Login setelah registrasi
        Auth::login($user);

        return redirect('/login'); // Arahkan ke halaman login setelah registrasi
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Cek kredensial login
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Arahkan ke dashboard berdasarkan role pengguna
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard'); // Arahkan ke dashboard admin
            } elseif ($user->role === 'kasir') {
                return redirect()->route('kasir.dashboard'); // Arahkan ke dashboard kasir
            } else {
                return redirect('/'); // Arahkan ke halaman utama untuk pelanggan
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login'); // Arahkan kembali ke halaman login setelah logout
    }
}


