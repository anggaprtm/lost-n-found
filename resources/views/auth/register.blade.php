@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="min-h-screen flex">

    {{-- LEFT SIDE - REGISTER FORM --}}
    <div class="w-full lg:w-[35%] flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md">
            
            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Pendaftaran Akun <span class="text-[#741B47]">Lost & Found</span>
                </h1>
                <p class="text-sm text-gray-600">
                    Buat akun baru untuk memulai menggunakan sistem
                </p>
            </div>

            {{-- FORM --}}
            <form method="POST" action="{{ route('register.attempt') }}" class="space-y-4">
                @csrf

                {{-- NAMA LENGKAP --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap
                    </label>
                    <input id="name" 
                           name="name" 
                           type="text" 
                           required
                           value="{{ old('name') }}"
                           placeholder="Masukkan nama lengkap Anda"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-[#741B47] focus:border-transparent
                                  transition-all text-sm">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- NIM --}}
                <div>
                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Induk Mahasiswa (NIM)
                    </label>
                    <input id="nim" 
                           name="nim" 
                           type="text" 
                           required
                           value="{{ old('nim') }}"
                           placeholder="Contoh: 164231xxx"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-[#741B47] focus:border-transparent
                                  transition-all text-sm">
                    @error('nim')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Email
                    </label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           required
                           value="{{ old('email') }}"
                           placeholder="nama@ftmm.unair.ac.id"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-[#741B47] focus:border-transparent
                                  transition-all text-sm">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           required
                           placeholder="Minimal 8 karakter"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-[#741B47] focus:border-transparent
                                  transition-all text-sm">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password
                    </label>
                    <input id="password_confirmation" 
                           name="password_confirmation" 
                           type="password" 
                           required
                           placeholder="Masukkan password sekali lagi"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-[#741B47] focus:border-transparent
                                  transition-all text-sm">
                </div>

                {{-- TERMS & CONDITIONS --}}
                <div class="flex items-start">
                    <input type="checkbox" 
                           name="terms" 
                           id="terms" 
                           required
                           class="mt-1 w-4 h-4 rounded border-gray-300 text-[#741B47] 
                                  focus:ring-2 focus:ring-[#741B47] focus:ring-offset-0">
                    <label for="terms" class="ml-2 text-sm text-gray-600">
                        Saya setuju dengan <a href="#" class="text-[#741B47] hover:text-[#5f163a] font-medium">syarat dan ketentuan</a> yang berlaku
                    </label>
                </div>

                {{-- SUBMIT BUTTON --}}
                <button type="submit"
                        class="w-full mt-6 py-3 px-4 bg-[#6366f1] hover:bg-[#4f46e5]
                               text-white font-semibold rounded-lg
                               shadow-md hover:shadow-lg
                               transition-all duration-200
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6366f1]">
                    Daftar Sekarang
                </button>

                {{-- LOGIN LINK --}}
                <p class="text-center text-sm text-gray-600 mt-6">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" 
                       class="font-semibold text-[#741B47] hover:text-[#5f163a]">
                        Masuk di sini
                    </a>
                </p>

                {{-- HOME LINK --}}
                <p class="text-center text-sm text-gray-600">
                    <a href="/" 
                       class="font-medium text-gray-500 hover:text-gray-700">
                        ← Kembali ke Beranda
                    </a>
                </p>
            </form>

        </div>
    </div>

    {{-- RIGHT SIDE - IMAGE --}}
    <div class="hidden lg:block lg:w-[65%] relative">
        {{-- Background Image --}}
        <div class="absolute inset-0">
            <img src="/images/GKB.jpg" 
                 class="w-full h-full object-cover" 
                 alt="FTMM Gedung">
            
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-br from-[#073763]/70 via-[#073763]/60 to-[#741B47]/70"></div>
        </div>

        {{-- Welcome Text --}}
        <div class="relative h-full flex flex-col items-center justify-center text-white px-12">
            
            {{-- Icon --}}
            <div class="mb-8">
                <div class="h-24 w-24 rounded-2xl bg-white/20 backdrop-blur-sm
                            flex items-center justify-center
                            border border-white/30 shadow-2xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
            </div>

            {{-- Main Text --}}
            <h2 class="text-5xl font-bold mb-4 drop-shadow-lg text-center">
                Bergabunglah!
            </h2>
            
            {{-- Subtitle --}}
            <p class="text-xl text-white/90 mb-2 font-medium">
                Lost & Found System
            </p>
            <p class="text-lg text-white/80 mb-8">
                FTMM, Gedung Nano
            </p>

            {{-- Features List --}}
            <div class="mt-8 max-w-md space-y-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-medium">Laporan Mudah</p>
                        <p class="text-white/70 text-sm">Laporkan barang hilang atau temuan dengan cepat</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-medium">Notifikasi Real-time</p>
                        <p class="text-white/70 text-sm">Dapatkan update langsung tentang barang Anda</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-medium">Aman & Terpercaya</p>
                        <p class="text-white/70 text-sm">Data Anda dijaga dengan keamanan tinggi</p>
                    </div>
                </div>
            </div>

            {{-- Footer Badge --}}
            <div class="absolute bottom-8 left-0 right-0 flex justify-center gap-6 px-12">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-3 border border-white/20">
                    <p class="text-sm text-white/70">Fakultas Teknologi Maju dan Multidisiplin</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection