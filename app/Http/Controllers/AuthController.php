<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        if (auth()->check()) {
            // Redirect berdasarkan role
            return $this->redirectBasedOnRole();
        }
        return view('auth.register');
    }

    public function showLoginForm()
    {
        if (auth()->check()) {
            // Redirect berdasarkan role
            return $this->redirectBasedOnRole();
        }
        return view('auth.login');
    }

    // Method helper untuk redirect berdasarkan role
    private function redirectBasedOnRole()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'petugas') {
            return redirect()->route('petugas.dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    }



    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nim' => ['required', 'string', 'max:255', 'unique:users'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nim' => $request->nim,
            'role' => 'pengguna', // Default role for registration
        ]);

        // Log the user in
        auth()->login($user);

        // Redirect to a dashboard or home page
        return redirect('/dashboard');
        
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
        return redirect()->route('landing');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

}
