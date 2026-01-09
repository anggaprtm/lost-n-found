@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <div class="mb-6">
        <a href="{{ route('petugas.reports.show', $report) }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            &larr; Kembali ke Detail Laporan
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-[#073763] to-[#073763] px-6 py-8">
            <h1 class="text-2xl font-bold text-white">Assign Claim untuk Barang Temuan</h1>
            <p class="text-[#C0C0C0] mt-2">Detail barang yang akan di-assign.</p>
        </div>

        <div class="p-6 space-y-6">
            {{-- Detail Barang --}}
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Barang</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        @if($report->photo)
                            <img src="{{ asset('storage/' . $report->photo) }}" alt="Foto Barang" class="w-full h-auto object-cover rounded-lg shadow-md">
                        @else
                            <img src="{{ asset('placeholder.jpg') }}" alt="Tidak ada foto" class="w-full h-auto object-cover rounded-lg shadow-md">
                        @endif
                    </div>
                    <div class="md:col-span-2 space-y-3">
                        <h3 class="text-xl font-bold text-gray-900">{{ $report->item_name }}</h3>
                        <p class="text-gray-600">{{ $report->description }}</p>
                        <div class="text-sm">
                            <p><strong>Kategori:</strong> {{ $report->category->name }}</p>
                            <p><strong>Lokasi:</strong> {{ $report->room->building->name }} - {{ $report->room->name }}</p>
                            <p><strong>Tanggal Ditemukan:</strong> {{ \Carbon\Carbon::parse($report->event_date)->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Assign --}}
            <div x-data="{ assign_type: '{{ old('assign_type', 'existing') }}' }">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Pilih Metode Assign</h2>
                
                <form action="{{ route('petugas.reports.assign.store', $report) }}" method="POST" class="space-y-6">
                    @csrf

                    <input type="hidden" name="assign_type" x-model="assign_type">

                    <fieldset class="flex space-x-4">
                        <legend class="sr-only">Tipe Assign</legend>
                        <div>
                            <input type="radio" name="assign_type_option" id="assign_existing" value="existing" x-model="assign_type">
                            <label for="assign_existing" class="font-medium text-gray-700">Pilih Pengguna Terdaftar</label>
                        </div>
                        <div>
                            <input type="radio" name="assign_type_option" id="assign_manual" value="manual" x-model="assign_type">
                            <label for="assign_manual" class="font-medium text-gray-700">Input Manual (Non-Akun)</label>
                        </div>
                    </fieldset>

                    {{-- Existing User Selection --}}
                    <div x-show="assign_type === 'existing'" x-transition>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Nama Pengguna (Mahasiswa)</label>
                        <select class="form-select w-full border-[#073763] focus:ring-[#073763] rounded-md shadow-sm @error('user_id') border-red-500 @enderror" 
                                id="user_id" name="user_id" x-bind:required="assign_type === 'existing'">
                            <option value="">-- Pilih Pengguna --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Manual User Input --}}
                    <div x-show="assign_type === 'manual'" x-transition class="space-y-4">
                        <div>
                            <label for="claimer_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="claimer_name" id="claimer_name" value="{{ old('claimer_name') }}"
                                   x-bind:required="assign_type === 'manual'"
                                   class="form-input w-full border-[#073763] focus:ring-[#073763] rounded-md shadow-sm @error('claimer_name') border-red-500 @enderror" 
                                   placeholder="Contoh: Budi Sanjaya">
                            @error('claimer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="claimer_id_number" class="block text-sm font-medium text-gray-700 mb-2">NIM / Nomor Induk</label>
                            <input type="text" name="claimer_id_number" id="claimer_id_number" value="{{ old('claimer_id_number') }}"
                                   x-bind:required="assign_type === 'manual'"
                                   class="form-input w-full border-[#073763] focus:ring-[#073763] rounded-md shadow-sm @error('claimer_id_number') border-red-500 @enderror" 
                                   placeholder="Contoh: 220101001">
                             @error('claimer_id_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Dengan menekan tombol "Assign Claim", sebuah klaim akan dibuat dan langsung ditandai sebagai <strong>Approved</strong>. Status barang ini juga akan otomatis berubah menjadi <strong>Returned</strong>.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('petugas.reports.show', $report) }}" class="btn-outline border-[#741847] text-[#741847] hover:bg-[#741847] hover:text-white">Batal</a>
                        <button type="submit" class="btn-primary bg-[#741847] hover:bg-[#5b132f]">Assign Claim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
