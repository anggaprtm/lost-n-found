@extends('layouts.app')

@section('title', 'Daftar Akun Mahasiswa')

@section('content')
<div class="min-h-screen bg-cover bg-center flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative" style="background-image: url('/images/GKB.jpg');">
    
    <div class="absolute inset-0 bg-gradient-to-br from-[#073763]/90 via-[#073763]/80 to-[#741B47]/90 backdrop-blur-[2px]"></div>

    <div class="max-w-md w-full space-y-6 z-10 relative">
        
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-white/20 rounded-2xl flex items-center justify-center mb-6 shadow-lg backdrop-blur-md border border-white/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2 drop-shadow-md font-sans">Pendaftaran Akun</h2>
            <p class="text-blue-100 text-sm">Buat akun baru untuk memulai.</p>
        </div>

        <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-8 border border-white/50">
            <form method="POST" action="{{ route('register.attempt') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input id="name" name="name" type="text" required
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#073763] focus:border-[#073763] transition-colors sm:text-sm" 
                        placeholder="Masukkan nama lengkap Anda" 
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="nim" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Induk Mahasiswa (NIM)</label>
                    <input id="nim" name="nim" type="text" required
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#073763] focus:border-[#073763] transition-colors sm:text-sm" 
                        placeholder="Contoh: 164231xxx" 
                        value="{{ old('nim') }}">
                    @error('nim')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                    <input id="email" name="email" type="email" required
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#073763] focus:border-[#073763] transition-colors sm:text-sm" 
                        placeholder="nama@ftmm.unair.ac.id" 
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input id="password" name="password" type="password" required
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#073763] focus:border-[#073763] transition-colors sm:text-sm" 
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#073763] focus:border-[#073763] transition-colors sm:text-sm" 
                        placeholder="••••••••">
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-gradient-to-r from-[#073763] to-[#0a4d8c] hover:from-[#052c50] hover:to-[#073763] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#073763] transition-all transform hover:-translate-y-0.5">
                    Daftar Sekarang
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-medium text-[#073763] hover:text-[#052c50] hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>
        
        <p class="text-center text-blue-200 text-xs mt-8 opacity-80">
            &copy; {{ date('Y') }} Lost & Found System. All rights reserved.
        </p>
    </div>
</div>
@endsection
