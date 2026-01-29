@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-[#073763] to-[#04223b] px-6 py-8">
            <h1 class="text-2xl font-bold text-white">{{ $report->item_name }}</h1>

            <div class="flex items-center space-x-4 mt-2">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                    {{ $report->type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                    {{ $report->type === 'lost' ? 'Barang Hilang' : 'Barang Temuan' }}
                </span>
                    @php
                        $statusMap = [
                            'pending' => ['label' => 'Menunggu Validasi', 'class' => 'bg-yellow-100 text-yellow-800'],
                            'approved' => ['label' => 'Tervalidasi', 'class' => 'bg-blue-100 text-blue-800'],
                            'rejected' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-800'],
                            'returned' => ['label' => 'Dikembalikan', 'class' => 'bg-purple-100 text-purple-800'],
                        ];
                    @endphp

                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                        {{ $statusMap[$report->status]['class'] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusMap[$report->status]['label'] ?? ucfirst($report->status) }}
                    </span>
                    
            </div>
        </div>

        <div class="p-6">

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
                <script>
                    alert("{{ session('success') }}");
                </script>
            @endif

            {{-- ALERT ERROR --}}
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            {{-- DETAIL --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    @if($report->photo)
                        <a href="{{ Storage::url($report->photo) }}" target="_blank">
                            <img src="{{ Storage::url($report->photo) }}"
                                 alt="{{ $report->item_name }}"
                                 onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=Image+Not+Found'"
                                 class="w-full h-auto object-cover rounded-lg border cursor-pointer hover:opacity-90 transition-opacity">
                        </a>
                    @else
                        <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center border">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Kategori</label>
                        <p class="text-lg text-gray-900">{{ $report->category->name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Lokasi</label>
                        <p class="text-lg text-gray-900">
                            {{ $report->room->name }}, {{ $report->room->building->name }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Tanggal Kejadian</label>
                        <p class="text-lg text-gray-900">{{ $report->event_date->format('d M Y') }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Pelapor</label>
                        <p class="text-lg text-gray-900">{{ $report->user->name }}</p>
                    </div>
                </div>
            </div>

            {{-- DESKRIPSI --}}
            <div class="mb-8 border-t pt-6">
                <label class="text-sm font-medium text-gray-500 mb-2">Deskripsi Lengkap</label>
                <p class="text-gray-800 leading-relaxed">{{ $report->description }}</p>
            </div>

            {{-- LIST KLAIM (hanya untuk pelapor) --}}
            @if(auth()->id() === $report->user_id && $report->claims->count() > 0)
                <div class="bg-gray-50 rounded-xl p-6 mb-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Klaim untuk Barang Ini</h3>

                    <div class="space-y-4">
                        @foreach($report->claims as $claim)
                            <div class="bg-white rounded-lg p-4 border">
                                <p class="font-medium text-gray-900">{{ $claim->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $claim->user->nim }}</p>
                                <p class="text-sm text-gray-700 italic mt-2">
                                    "{{ Str::limit($claim->description, 100) }}"
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- FOOTER --}}
            <div class="flex justify-between items-center mt-8 pt-6 border-t">

                {{-- TOMBOL KEMBALI --}}
                <a href="{{ url()->previous() }}" class="btn-primary">Kembali</a>

                {{-- TOMBOL KLAIM --}}
                @php
                    $viewer = auth()->user();
                    $viewerIsOwner = $viewer->id === $report->user_id;
                    $ownerIsStaff = in_array($report->user->role, ['admin','petugas']);
                @endphp

                @if(
                    $report->type === 'found' &&
                    $report->status === 'approved' &&
                    (!$viewerIsOwner || $ownerIsStaff) &&
                    !$report->claims()->where('user_id', auth()->id())->exists()
                )
                    <button onclick="openClaimModal()" class="btn-primary">
                        Klaim
                    </button>
                @endif
                
                {{-- Tombol Assign (hanya untuk petugas/admin) --}}
                @if(in_array(auth()->user()->role, ['petugas', 'admin']) && $report->type === 'found' && $report->status === 'approved')
                    <a href="{{ route('petugas.reports.assign', $report) }}" class="btn-primary">
                        Kembalikan
                    </a>
                @endif

            </div>

        </div>
    </div>
</div>

{{-- MODAL KLAIM --}}
<div id="claimModal"
     class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md">

        <h2 class="text-lg font-semibold mb-3">Ajukan Klaim Barang</h2>

        <form method="POST" action="{{ route('claims.store') }}">
            @csrf
            <input type="hidden" name="report_id" value="{{ $report->id }}">

            <label class="block text-sm font-medium text-gray-600">Deskripsi Klaim</label>
            @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
                {{ $errors->first() }}
            </div>
            @endif
            <textarea name="description" rows="4" required
                      class="w-full border rounded-lg p-2 mt-2"
                      placeholder="Jelaskan ciri-ciri atau bukti bahwa barang ini milik Anda..."></textarea>

            <div class="flex justify-end space-x-3 mt-4">
                <button type="button"
                        onclick="closeClaimModal()"
                        class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Kirim Klaim
                </button>
            </div>
        </form>

    </div>
</div>

{{-- SCRIPT MODAL --}}
<script>
function openClaimModal() {
    document.getElementById('claimModal').classList.remove('hidden');
}
function closeClaimModal() {
    document.getElementById('claimModal').classList.add('hidden');
}
</script>

@if($errors->any() || session('error'))
<script>
    document.getElementById('claimModal').classList.remove('hidden');
</script>
@endif


@endsection
