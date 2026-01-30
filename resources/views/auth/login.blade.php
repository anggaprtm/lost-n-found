@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex">

    {{-- LEFT SIDE - LOGIN FORM --}}
    <div class="w-full lg:w-[35%] flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md">
            
            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Aplikasi <span class="text-[#741B47]">Lost & Found</span> FTMM
                </h1>
                <p class="text-sm text-gray-600">
                    Sistem Pelaporan Barang Hilang & Temuan
                </p>
            </div>

            {{-- TAB SWITCHER --}}
            <div class="mb-6">
                <div class="flex gap-2 border-b border-gray-200">
                    <button type="button" id="userTab" 
                            class="px-4 py-2 text-sm font-semibold border-b-2 border-[#741B47] text-[#741B47] transition-all">
                        Mahasiswa
                    </button>
                    <button type="button" id="adminTab" 
                            class="px-4 py-2 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all">
                        Admin / Petugas
                    </button>
                </div>
            </div>

            {{-- FORM --}}
            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="login_type" id="loginType" value="pengguna">

                {{-- FORM MAHASISWA (NIM) --}}
                <div id="userForm" class="transition-all duration-300">
                    <label for="nomor_induk" class="block text-sm font-medium text-gray-700 mb-2">
                        NIM atau Email
                    </label>
                    <input id="nomor_induk" 
                           name="nomor_induk" 
                           type="text"
                           value="{{ old('nomor_induk') }}"
                           placeholder="Masukkan NIM Anda"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-[#741B47] focus:border-transparent
                                  transition-all text-sm">
                    @error('nomor_induk')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FORM ADMIN (EMAIL) --}}
                <div id="adminForm" class="hidden transition-all duration-300">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email atau NIP
                    </label>
                    <input id="email" 
                           name="email" 
                           type="text"
                           value="{{ old('email') }}"
                           placeholder="Masukkan Email Staff"
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
                           placeholder="Masukkan Password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-[#741B47] focus:border-transparent
                                  transition-all text-sm">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ERROR UMUM --}}
                @if($errors->has('login'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-red-600">{{ $errors->first('login') }}</p>
                    </div>
                @endif

                {{-- REMEMBER ME --}}
                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="remember"
                               class="w-4 h-4 rounded border-gray-300 text-[#741B47] 
                                      focus:ring-2 focus:ring-[#741B47] focus:ring-offset-0">
                        <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-sm text-[#741B47] hover:text-[#5f163a] font-medium">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- SUBMIT BUTTON --}}
                <button type="submit"
                        class="w-full py-3 px-4 bg-[#6366f1] hover:bg-[#4f46e5]
                               text-white font-semibold rounded-lg
                               shadow-md hover:shadow-lg
                               transition-all duration-200
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6366f1]">
                    Login
                </button>

                {{-- REGISTER LINK --}}
                <p class="text-center text-sm text-gray-600 mt-6">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" 
                       class="font-semibold text-[#741B47] hover:text-[#5f163a]">
                        Daftar di sini
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
            <img src="/images/GKB.JPG" 
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
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            {{-- Main Text --}}
            <h2 class="text-5xl font-bold mb-4 drop-shadow-lg text-center">
                Selamat Datang!
            </h2>
            
            {{-- Subtitle --}}
            <p class="text-xl text-white/90 mb-2 font-medium">
                Lost & Found System
            </p>
            <p class="text-lg text-white/80">
                FTMM, Gedung Nano
            </p>

            {{-- Description --}}
            <div class="mt-12 max-w-md text-center">
                <p class="text-white/80 leading-relaxed">
                    Sistem terintegrasi untuk membantu menemukan barang hilang dan 
                    mengembalikan barang temuan kepada pemiliknya
                </p>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userTab = document.getElementById('userTab');
    const adminTab = document.getElementById('adminTab');
    const userForm = document.getElementById('userForm');
    const adminForm = document.getElementById('adminForm');
    const loginType = document.getElementById('loginType');
    const userInput = document.getElementById('nomor_induk');
    const adminInput = document.getElementById('email');

    function switchTab(activeTab, inactiveTab, activeForm, inactiveForm, type) {
        // Style Tab Aktif
        activeTab.classList.add('border-[#741B47]', 'text-[#741B47]');
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        
        // Style Tab Non-Aktif
        inactiveTab.classList.remove('border-[#741B47]', 'text-[#741B47]');
        inactiveTab.classList.add('border-transparent', 'text-gray-500');
        
        // Toggle Form
        activeForm.classList.remove('hidden');
        inactiveForm.classList.add('hidden');
        
        // Logic Disable Input
        if (type === 'pengguna') {
            userInput.disabled = false;
            adminInput.disabled = true;
            adminInput.value = '';
            userInput.focus();
        } else {
            userInput.disabled = true;
            adminInput.disabled = false;
            userInput.value = '';
            adminInput.focus();
        }
        
        loginType.value = type;
    }

    userTab.addEventListener('click', () => switchTab(userTab, adminTab, userForm, adminForm, 'pengguna'));
    adminTab.addEventListener('click', () => switchTab(adminTab, userTab, adminForm, userForm, 'staf'));

    // Inisialisasi awal
    adminInput.disabled = true;
});
</script>
@endsection