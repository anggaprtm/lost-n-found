@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Halo, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-gray-500 mt-1">Pantau status laporanmu dan lihat kontribusimu.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-primary border border-blue-100">
                {{ auth()->user()->role === 'mahasiswa' ? 'Civitas Academica' : ucfirst(auth()->user()->role) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-yellow-400 hover:shadow-md transition duration-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Sedang Diproses</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $myStats['pending'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Menunggu validasi petugas</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg text-yellow-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-[#073763] to-[#0a4d8c] rounded-xl p-6 text-white shadow-lg transform hover:-translate-y-1 transition duration-200 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Siap Diambil / Ditemukan</p>
                    <p class="text-3xl font-bold mt-1">{{ $myStats['ready'] }}</p>
                    <p class="text-xs text-blue-200 mt-1">Segera cek ke pusat layanan!</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-green-500 hover:shadow-md transition duration-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Selesai</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $myStats['history'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Kasus ditutup</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <div class="bg-white rounded-xl shadow-sm p-6 relative overflow-hidden border border-gray-100">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-purple-50 rounded-full blur-xl"></div>
            
            <h2 class="text-lg font-bold text-gray-900 mb-2">🏅 Skor Kebaikan Anda</h2>
            <p class="text-sm text-gray-500 mb-6">Terima kasih sudah jujur melaporkan barang temuan.</p>
            
            <div class="flex items-center space-x-4 mb-6">
                <div class="flex-shrink-0 bg-purple-100 p-4 rounded-full">
                    <span class="text-3xl">
                        @if($gamification['level'] == 'Pahlawan Kampus') 🦸‍♂️
                        @elseif($gamification['level'] == 'Orang Baik') 😇
                        @else 👤
                        @endif
                    </span>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500">Level Saat Ini</p>
                    <p class="text-xl font-bold text-purple-700">{{ $gamification['level'] }}</p>
                    <p class="text-xs text-gray-400">Total Temuan: {{ $gamification['score'] }} item</p>
                </div>
            </div>

            <div class="w-full bg-gray-100 rounded-full h-2.5 mb-1">
                <div class="bg-purple-600 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $gamification['progress'] }}%"></div>
            </div>
            <p class="text-xs text-right text-purple-600 font-medium mt-2">{{ round($gamification['progress']) }}% menuju level berikutnya!</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-secondary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Daerah Rawan (Barang Ditemukan / Hilang)
                </h2>
                <span class="text-xs bg-red-50 text-secondary px-2 py-1 rounded font-medium border border-red-100">Hati-hati!</span>
            </div>
            
            <div class="space-y-4">
                @forelse($rawanZones as $index => $zone)
                <div class="flex items-center">
                    <span class="w-6 text-sm font-bold text-gray-400">#{{ $index + 1 }}</span>
                    <div class="flex-1 ml-3">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-800">{{ $zone->name }}</span>
                            <span class="text-secondary font-bold">{{ $zone->total }} Kasus</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-secondary h-1.5 rounded-full opacity-80" style="width: {{ ($zone->total / ($rawanZones->first()->total ?? 1)) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <p class="text-sm text-gray-500">Data belum tersedia. Kampus aman! 👍</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">📝 Laporan Terakhir</h2>
                    <a href="{{ route('reports.index') }}" class="text-sm text-primary hover:underline font-medium">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentReports as $report)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition group">
                        <div class="flex items-center space-x-3">
                            <span class="p-2 rounded-lg {{ $report->type == 'lost' ? 'bg-red-50 text-secondary' : 'bg-green-50 text-green-600' }}">
                                @if($report->type == 'lost')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                @endif
                            </span>
                            <div>
                                <p class="text-sm font-medium text-gray-900 group-hover:text-primary transition-colors">{{ $report->item_name }}</p>
                                <p class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }} • {{ $report->room->building->name ?? 'Lokasi tidak ada' }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full 
                            {{ $report->status == 'pending' ? 'bg-yellow-50 text-yellow-700' : 
                              ($report->status == 'approved' || $report->status == 'found' ? 'bg-blue-50 text-blue-700' : 
                              'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($report->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada laporan.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">🙋‍♂️ Klaim Terakhir</h2>
                    <a href="{{ route('claims.index') }}" class="text-sm text-primary hover:underline font-medium">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentClaims as $claim)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition group">
                        <div>
                            <p class="text-sm font-medium text-gray-900 group-hover:text-primary transition-colors">Klaim: {{ $claim->report->item_name }}</p>
                            <p class="text-xs text-gray-500">{{ $claim->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full 
                            {{ $claim->status == 'pending' ? 'bg-yellow-50 text-yellow-700' : 
                              ($claim->status == 'approved' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700') }}">
                            {{ ucfirst($claim->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada klaim.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24 border border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 mb-4">🚀 Aksi Cepat</h2>
                <div class="space-y-3">
                    <a href="{{ route('reports.create') }}" class="block w-full text-center px-4 py-3 bg-primary text-white font-medium rounded-lg hover:bg-[#0a4d8c] shadow-md transition transform hover:-translate-y-0.5">
                        + Buat Laporan Baru
                    </a>
                    
                    <a href="{{ route('reports.public_index') }}" class="block w-full text-center px-4 py-3 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 hover:text-primary hover:border-primary transition">
                        🔍 Cari Barang Temuan
                    </a>

                    <div class="border-t border-gray-100 my-4 pt-4">
                        <p class="text-xs text-gray-500 mb-2">Butuh bantuan?</p>
                        <a href="{{ route('profile.show') }}" class="flex items-center text-sm text-gray-600 hover:text-primary transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Pengaturan Akun
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection