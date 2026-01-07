<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim',
            'email' => 'nullable|email|unique:users,email',
            'password' => ['required', Password::defaults()],
            'phone' => 'nullable|string|max:20',
        ]);

        $role = str_starts_with($request->nim, 'P') ? 'petugas' : 'pengguna';

        User::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $role = str_starts_with($request->nim, 'P') ? 'petugas' : 'pengguna';

        $user->update([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Validasi jika pengguna adalah admin (contoh kasus tidak bisa menghapus admin)
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Tidak bisa menghapus pengguna admin.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
                        ->with('success', 'Pengguna berhasil dihapus');
    }


}
