@extends('layouts.app')

@section('title', 'Login')

@section('content')
{{-- Latar Belakang Full Screen --}}
<div class="min-h-screen bg-cover bg-center flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative" style="background-image: url('/images/GKB.jpg');">
    
    {{-- Overlay Gelap dengan Gradasi (Biar teks terbaca jelas) --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#073763]/90 via-[#073763]/80 to-[#741B47]/90 backdrop-blur-[2px]"></div>
    
    {{-- Card Login --}}
    <div class="max-w-md w-full space-y-8 z-10 relative">
        {{-- Header Logo & Teks --}}
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-white/20 rounded-2xl flex items-center justify-center mb-6 shadow-lg backdrop-blur-md border border-white/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2 drop-shadow-md font-sans">Lost & Found</h2>
            <p class="text-blue-100 text-sm">Sistem Pelaporan Barang Hilang & Temuan</p>
        </div>

        {{-- Kotak Putih Form --}}
        <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-8 border border-white/50">
            
            {{-- Tab Switcher (Pengguna vs Admin) --}}
            <div class="mb-8">
                <div class="flex bg-gray-100 rounded-xl p-1 shadow-inner">
                    <button type="button" id="userTab" class="flex-1 py-2.5 px-4 rounded-lg text-sm font-semibold transition-all duration-300 bg-[#073763] text-white shadow-md transform scale-100">
                        Mahasiswa
                    </button>
                    <button type="button" id="adminTab" class="flex-1 py-2.5 px-4 rounded-lg text-sm font-semibold transition-all duration-300 text-gray-500 hover:text-gray-900 hover:bg-gray-200">
                        Admin / Petugas
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="login_type" id="loginType" value="pengguna">

                {{-- FORM PENGGUNA (NIM) --}}
                <div id="userForm" class="transition-all duration-300">
                    <div>
                        <label for="nomor_induk" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Induk (NIM)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.5 2-2 2h4c-1.5 0-2-1.116-2-2z" />
                                </svg>
                            </div>
                            <input id="nomor_induk" name="nomor_induk" type="text" 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#073763] focus:border-[#073763] transition-colors sm:text-sm" 
                                placeholder="Contoh: 164231xxx" 
                                value="{{ old('nomor_induk') }}">
                        </div>
                        @error('nomor_induk')
                            <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- FORM ADMIN (EMAIL) --}}
                <div id="adminForm" class="hidden transition-all duration-300">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Staff</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#073763] focus:border-[#073763] transition-colors sm:text-sm" 
                                placeholder="nama@ftmm.unair.ac.id" 
                                value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- PASSWORD (SHARED) --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#073763] focus:border-[#073763] transition-colors sm:text-sm" 
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Error Umum --}}
                @if($errors->has('login'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-red-600 font-medium">{{ $errors->first('login') }}</p>
                    </div>
                @endif

                {{-- Tombol Login --}}
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-gradient-to-r from-[#073763] to-[#0a4d8c] hover:from-[#052c50] hover:to-[#073763] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#073763] transition-all transform hover:-translate-y-0.5">
                    Masuk Sekarang
                </button>

                <p class="text-center text-sm text-gray-600 mt-6">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-medium text-[#073763] hover:text-[#052c50] hover:underline">
                        Daftar di sini
                    </a>
                </p>
                <p class="text-center text-sm text-gray-600">
                    Kembali ke
                    <a href="/" class="font-medium text-[#073763] hover:text-[#052c50] hover:underline">
                        Beranda
                    </a>
                </p>
            </form>
        </div>
        
        {{-- Footer Kecil --}}
        <p class="text-center text-blue-200 text-xs mt-8 opacity-80">
            &copy; {{ date('Y') }} Lost & Found System. All rights reserved.
        </p>
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
        activeTab.classList.add('bg-[#073763]', 'text-white', 'shadow-md');
        activeTab.classList.remove('text-gray-500', 'hover:bg-gray-200');
        
        // Style Tab Non-Aktif
        inactiveTab.classList.remove('bg-[#073763]', 'text-white', 'shadow-md');
        inactiveTab.classList.add('text-gray-500', 'hover:bg-gray-200');
        
        // Toggle Form
        activeForm.classList.remove('hidden');
        inactiveForm.classList.add('hidden');
        
        // Logic Disable Input
        if (type === 'pengguna') {
            userInput.disabled = false;
            adminInput.disabled = true;
            adminInput.value = ''; // Reset nilai
            userInput.focus(); // Auto focus
        } else {
            userInput.disabled = true;
            adminInput.disabled = false;
            userInput.value = ''; // Reset nilai
            adminInput.focus(); // Auto focus
        }
        
        loginType.value = type;
    }

    userTab.addEventListener('click', () => switchTab(userTab, adminTab, userForm, adminForm, 'pengguna'));
    adminTab.addEventListener('click', () => switchTab(adminTab, userTab, adminForm, userForm, 'staf'));

    // Inisialisasi awal (Pastikan bersih)
    adminInput.disabled = true;
});
</script>
@endsection