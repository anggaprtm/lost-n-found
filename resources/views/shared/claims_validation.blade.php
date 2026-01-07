@extends('layouts.app')

@section('title', 'Manajemen Klaim')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Validasi Klaim</h1>
            <p class="text-gray-600 mt-2">Kelola dan validasi klaim barang dari mahasiswa</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama barang atau pengklaim..." class="form-input">
            </div>
            <button type="submit" class="btn-secondary">Cari</button>
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.claims.validation') : route('petugas.claims') }}" class="btn-outline">
                    Reset
                </a>
        </form>
    </div>

    <!-- Claims List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($claims->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengklaim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Klaim</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($claims as $claim)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($claim->report->photo)
                                        <img src="{{ Storage::url($claim->report->photo) }}" alt="{{ $claim->report->item_name }}" 
                                             class="w-12 h-12 rounded-lg object-cover mr-4">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $claim->report->item_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $claim->report->category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $claim->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $claim->user->nim }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $claim->report->room->name }}<br>
                                <span class="text-gray-500">{{ $claim->report->room->building->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $claim->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2 justify-end">

                                    {{-- ======================================================= --}}
                                    {{-- == INILAH LOGIKA BARU UNTUK TOMBOL AKSI DINAMIS      == --}}
                                    {{-- ======================================================= --}}
                                    @if($claim->status === 'pending')
                                        {{-- Jika status PENDING, tampilkan tombol Validasi & Detail --}}
                                        <button onclick="showValidationModal({{ $claim->id }}, '{{ addslashes(optional($claim->report)->item_name) }}', '{{ addslashes($claim->description) }}')" 
                                                class="btn-primary-sm">
                                            Validasi
                                        </button>
                                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.claims.show', $claim) : route('petugas.claims.show', $claim) }}" 
                                        class="btn-secondary-sm">
                                            Detail
                                        </a>
                                    @else
                                        {{-- Jika status SUDAH DIPROSES, hanya tampilkan tombol Detail & Hapus --}}
                                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.claims.show', $claim) : route('petugas.claims.show', $claim) }}" 
                                        class="btn-secondary-sm">
                                            Detail
                                        </a>
                                        <button type="button" onclick="showClaimDeleteModal({{ $claim->id }})" class="btn-danger-sm">
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $claims->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada klaim pending</h3>
                <p class="mt-1 text-sm text-gray-500">Semua klaim telah divalidasi.</p>
            </div>
        @endif
    </div>
</div>

<!-- Validation Modal -->
<div id="validationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Validasi Klaim</h3>
            
            <form id="validationForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
                    <p id="reportName" class="text-gray-900 font-semibold"></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Kepemilikan</label>
                    <p id="claimDescription" class="text-gray-900 text-sm bg-gray-50 p-3 rounded"></p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keputusan</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="approved" class="form-radio text-green-600">
                            <span class="ml-2 text-sm text-gray-700">Setujui Klaim</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="rejected" class="form-radio text-red-600">
                            <span class="ml-2 text-sm text-gray-700">Tolak Klaim</span>
                        </label>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" id="notes" rows="3" class="form-textarea" 
                              placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeValidationModal()" class="btn-outline">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary">
                        Simpan Validasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="deleteClaimModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-4 shadow-xl">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Hapus</h3>
        <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus klaim ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteClaimModal()" type="button" class="btn-secondary">
                Batal
            </button>
            <form id="deleteClaimForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
<script>
// Tambahkan DUA fungsi ini di dalam tag <script>

function showClaimDeleteModal(claimId) {
    const form = document.getElementById('deleteClaimForm');
    
    // Logika untuk menentukan action URL berdasarkan role user
    @if(Auth::user()->role === 'admin')
        form.action = `/admin/claims/${claimId}`;
    @else
        form.action = `/petugas/claims/${claimId}`;
    @endif
    
    const modal = document.getElementById('deleteClaimModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteClaimModal() {
    const modal = document.getElementById('deleteClaimModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
// Fungsi untuk membuka modal validasi
function showValidationModal(claimId, reportName, claimDescription) {
    const form = document.getElementById('validationForm');
    const reportNameSpan = document.getElementById('reportName');
    const claimDescriptionP = document.getElementById('claimDescription');
    const modal = document.getElementById('validationModal');

    if (!form || !reportNameSpan || !modal || !claimDescriptionP) {
        console.error('Satu atau lebih elemen modal tidak ditemukan!');
        return; 
    }

    reportNameSpan.textContent = reportName;
    claimDescriptionP.textContent = claimDescription; // Tampilkan deskripsi klaim

    @if(Auth::user()->role === 'admin')
        form.action = `/admin/claims/${claimId}/validate`;
    @elseif(Auth::user()->role === 'petugas')
        form.action = `/petugas/claims/${claimId}/validate`;
    @endif
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Fungsi untuk menutup modal
function closeValidationModal() {
    const modal = document.getElementById('validationModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('validationForm').reset();
}

// Event listener untuk menutup modal saat mengklik di luar area kontennya
document.getElementById('validationModal').addEventListener('click', function(e) {
    // Pastikan yang diklik adalah latar belakang gelap, bukan konten di dalamnya
    if (e.target === this) {
        closeValidationModal();
    }
});
</script>
@endsection
