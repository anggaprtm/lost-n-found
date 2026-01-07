@extends('layouts.app')

@section('title', 'Detail Klaim')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        {{-- Header dengan Warna Baru --}}
        <div class="bg-gradient-to-r from-[#073763] to-[#04223b] px-6 py-8">
            <h1 class="text-2xl font-bold text-white">Detail Klaim</h1>
            <p class="text-blue-100 mt-2">Untuk barang: {{ $claim->report->item_name }}</p>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Status Klaim</h2>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        @switch($claim->status)
                            @case('pending') bg-yellow-100 text-yellow-800 @break
                            @case('approved') bg-green-100 text-green-800 @break
                            @case('rejected') bg-red-100 text-red-800 @break
                        @endswitch">
                        {{ ucfirst($claim->status) }}
                    </span>
                </div>
                
                {{-- Notifikasi Status --}}
                @if($claim->status === 'approved')
                    <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <h3 class="text-sm font-medium text-green-800">Klaim Disetujui!</h3>
                                <p class="text-sm text-green-700 mt-1">
                                    Silakan ambil barang Anda di <strong>Kantor Kemahasiswaan</strong> pada jam kerja (08:00 - 16:00) dengan membawa identitas diri (KTM/KTP).
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($claim->status === 'rejected')
                    <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                         <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">Klaim Ditolak</h3>
                                <p class="text-sm text-red-700 mt-1">
                                    Klaim Anda tidak dapat disetujui. Silakan hubungi petugas untuk informasi lebih lanjut.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="border-t pt-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Klaim Anda</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Bukti Kepemilikan yang Anda Berikan</label>
                        <p class="text-gray-800 leading-relaxed bg-gray-50 border p-4 rounded-lg">{{ $claim->description }}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tanggal Klaim</label>
                            <p class="text-gray-900">{{ $claim->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        @if($claim->validator)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Divalidasi Oleh</label>
                                <p class="text-gray-900">{{ $claim->validator->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Barang Terkait</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Barang</label>
                        <p class="text-gray-900 font-semibold">{{ $claim->report->item_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Kategori</label>
                        <p class="text-gray-900">{{ $claim->report->category->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Lokasi Penemuan</label>
                        <p class="text-gray-900">{{ $claim->report->room->name }}, {{ $claim->report->room->building->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal Penemuan</label>
                        <p class="text-gray-900">{{ $claim->report->event_date->format('d M Y') }}</p>
                    </div>
                </div>
                 <div class="mt-4">
                    <a href="{{ route('reports.show', $claim->report) }}" class="text-sm font-semibold text-[#073763] hover:underline">
                        Lihat Detail Laporan Barang →
                    </a>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t flex justify-end">
                <a href="{{ Auth::user()->role === 'pengguna' ? route('claims.index') : (Auth::user()->role === 'petugas' ? route('petugas.claims') : route('admin.claims.validation')) }}" class="btn-primary">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection