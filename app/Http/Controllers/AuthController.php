<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan halaman auth (login + register dalam 1 file)
    public function showAuth()
    {
        return view('auth.auth');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required']
    ]);

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        // Cek status user dulu (opsional tapi bagus)
        if (Auth::user()->status === 'nonaktif') {
            Auth::logout();
            return back()->with('error', 'Akun tidak aktif');
        }

        // Redirect berdasarkan role
        if (Auth::attempt($credentials)) {
    $request->session()->regenerate();

    return redirect()->route('dashboard');
}
    }

    return back()
        ->withErrors(['email' => 'Email atau password salah'])
        ->withInput();
}


    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed']
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // auto hashed dari model
            'role' => 'petugas'
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Akun berhasil dibuat');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function profile()
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return view('auth.profile');
}

public function updateProfile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|max:255',
        'role' => 'required',
        'status' => 'required',
        'password' => 'nullable|min:6|confirmed',
    ]);

    // batasi role hanya admin / petugas
    if (!in_array($request->role, ['admin','petugas'])) {
        return back()->with('error', 'Role tidak valid');
    }

    // batasi status hanya aktif / nonaktif
    if (!in_array($request->status, ['aktif','nonaktif'])) {
        return back()->with('error', 'Status tidak valid');
    }

    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;
    $user->status = $request->status;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return back()->with('success', 'Profil berhasil diperbarui');
}




}
