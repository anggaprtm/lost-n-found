<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_type' => 'required|in:pengguna,staf',
            'password' => 'required',
        ]);

        $credentials = ['password' => $request->password];
        
        // Jika login sebagai Admin atau Petugas (staf)
        if ($request->login_type === 'staf') {
            $request->validate(['email' => 'required|email']);
            $credentials['email'] = $request->email;

            // Coba login sebagai admin
            if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
                $request->session()->regenerate();
                // Perbaikan: Gunakan route() untuk konsistensi
                return redirect()->intended(route('admin.dashboard'));
            }

            // Jika gagal, coba login sebagai petugas
            if (Auth::attempt(array_merge($credentials, ['role' => 'petugas']))) {
                $request->session()->regenerate();
                // Perbaikan: Gunakan route() untuk konsistensi
                return redirect()->intended(route('petugas.dashboard')); 
            }

        // Jika login sebagai Pengguna
        } else {
            $request->validate(['nomor_induk' => 'required']);
            
            // Kunci 'nim' harus sesuai dengan nama kolom di database Anda
            $credentials['nim'] = $request->nomor_induk;

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                
                // === PERUBAHAN UTAMA DI SINI ===
                // Arahkan pengguna ke dashboard mereka, bukan ke daftar laporan.
                return redirect()->intended(route('dashboard'));
            }
        }

        // Jika semua percobaan di atas gagal
        throw ValidationException::withMessages([
            'login' => ['Kredensial yang diberikan tidak cocok dengan data kami.'],
        ]);
    }

    public function guestAccess()
    {
        // Perbaikan: Arahkan ke landing page
        return redirect()->route('reports.public_index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}