@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

    {{-- ================= HEADER PROFILE ================= --}}
    <div class="relative mb-24">

        {{-- HEADER --}}
        <div class="h-48 bg-gradient-to-r from-[#741B47] to-[#073763] rounded-2xl shadow-md"></div>

        {{-- PROFILE WRAPPER --}}
        <div class="absolute left-8 top-full -translate-y-1/2 flex items-center gap-5">
            
            {{-- AVATAR --}}
            <div class="h-28 w-28 rounded-full border-4 border-white bg-white shadow-lg flex items-center justify-center overflow-hidden">
                <div class="h-full w-full bg-gray-100 flex items-center justify-center text-4xl font-bold text-[#073763]">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>

            {{-- NAME --}}
            <div>
                <h1 class="text-2xl font-bold text-gray-100 leading-tight">
                    {{ Auth::user()->name }}
                </h1>

                <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold uppercase">
                        {{ Auth::user()->role }}
                    </span>
                    <span>• Bergabung {{ Auth::user()->created_at->format('M Y') }}</span>
                </div>
            </div>

        </div>
    </div>


    {{-- ================= ALERT SUCCESS ================= --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show"
             class="mb-8 flex items-center justify-between bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
            <p class="text-sm font-medium text-green-700">
                {{ session('success') }}
            </p>
            <button @click="show = false" class="text-green-600 hover:text-green-800 text-lg leading-none">
                &times;
            </button>
        </div>
    @endif

    {{-- ================= CONTENT GRID ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- ================= SIDEBAR ================= --}}
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h3 class="text-sm font-semibold text-gray-800 mb-5">
                    Informasi Kontak
                </h3>

                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-400 mb-1">Email</p>
                        <p class="font-medium text-gray-700 break-all">
                            {{ Auth::user()->email }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400 mb-1">Nomor Induk</p>
                        <p class="font-medium text-gray-700">
                            {{ Auth::user()->nomor_induk ?? Auth::user()->nim ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="md:col-span-2 space-y-8">

            {{-- ================= BIODATA ================= --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h2 class="text-base font-semibold text-gray-900">
                        Biodata Diri
                    </h2>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Lengkap
                                </label>
                                <input type="text" name="name"
                                       value="{{ old('name', $user->name) }}"
                                       class="form-input">
                                @error('name')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">
                                        NIM / NIP
                                    </label>
                                    <input type="text"
                                           value="{{ $user->nim ?? $user->nomor_induk }}"
                                           disabled
                                           class="form-input bg-gray-50 text-gray-500 cursor-not-allowed">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">
                                        Email
                                    </label>
                                    <input type="email"
                                           value="{{ $user->email }}"
                                           disabled
                                           class="form-input bg-gray-50 text-gray-500 cursor-not-allowed">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    No. WhatsApp / Telepon
                                </label>
                                <input type="text" name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="08xxxxxxxxxx"
                                       class="form-input">
                                @error('phone')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ================= PASSWORD ================= --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h2 class="text-base font-semibold text-gray-900">
                        Keamanan Akun
                    </h2>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">

                            <div x-data="{ show: false }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Password Lama
                                </label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'"
                                           name="current_password"
                                           class="form-input pr-10">
                                    <button type="button"
                                            @click="show = !show"
                                            class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600">
                                        👁
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div x-data="{ show: false }">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Password Baru
                                    </label>
                                    <div class="relative">
                                        <input :type="show ? 'text' : 'password'"
                                               name="password"
                                               class="form-input pr-10">
                                        <button type="button"
                                                @click="show = !show"
                                                class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600">
                                            👁
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div x-data="{ show: false }">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Konfirmasi Password
                                    </label>
                                    <div class="relative">
                                        <input :type="show ? 'text' : 'password'"
                                               name="password_confirmation"
                                               class="form-input pr-10">
                                        <button type="button"
                                                @click="show = !show"
                                                class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600">
                                            👁
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                    class="btn-primary bg-yellow-500 hover:bg-yellow-600 border-none">
                                Ganti Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
