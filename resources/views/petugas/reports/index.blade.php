@extends('layouts.app')

@section('title', 'Validasi Laporan - Petugas')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Validasi Laporan</h1>
            <p class="text-gray-600">Kelola dan validasi laporan barang hilang & temuan</p>
        </div>
        <a href="{{ route('petugas.reports.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Laporan
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Pending Validasi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $reports->total() }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Barang Hilang</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $reports->where('type', 'lost')->count() }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Barang Temuan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $reports->where('type', 'found')->count() }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Laporan Menunggu Validasi</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($reports as $report)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        @if($report->photo)
                        <img class="h-16 w-16 rounded-lg object-cover" src="{{ Storage::url($report->photo) }}" alt="{{ $report->item_name }}">
                        @else
                        <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                        
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h4 class="text-lg font-medium text-gray-900">{{ $report->item_name }}</h4>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $report->type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $report->type === 'lost' ? 'Hilang' : 'Temuan' }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 mb-2">{{ Str::limit($report->description, 100) }}</p>
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $report->user->name }} ({{ $report->user->nim }})
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $report->room->name }}, {{ $report->room->building->name }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $report->event_date->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2 ml-4">
                        <button onclick="showValidationModal({{ $report->id }}, '{{ $report->item_name }}')" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Validasi
                        </button>
                        <a href="{{ Auth::user()->isAdmin()? route('admin.reports.show', $report): route('petugas.reports.show', $report) }}"
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-lg font-medium">Tidak ada laporan yang perlu divalidasi</p>
                <p class="text-sm">Semua laporan sudah diproses</p>
            </div>
            @endforelse
        </div>

        @if($reports->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $reports->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Validation Modal -->
<div id="validationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4 w-full">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Validasi Laporan</h3>
        <p class="text-sm text-gray-600 mb-6">Pilih status untuk laporan: <span id="reportName" class="font-medium"></span></p>
        
        <form id="validationForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="status" value="approved" class="mr-2" required>
                        <span class="text-green-700">Setujui Laporan</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status" value="rejected" class="mr-2" required>
                        <span class="text-red-700">Tolak Laporan</span>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="admin_notes" id="admin_notes" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeValidationModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors">
                    Validasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const baseValidateUrl = "{{ Auth::user()->isAdmin() ? '/admin/reports' : '/petugas/reports' }}";

function showValidationModal(reportId, reportName) {
    document.getElementById('reportName').textContent = reportName;
    document.getElementById('validationForm').action =
        `${baseValidateUrl}/${reportId}/validate`;
    document.getElementById('validationModal').classList.remove('hidden');
    document.getElementById('validationModal').classList.add('flex');
}


function closeValidationModal() {
    document.getElementById('validationModal').classList.add('hidden');
    document.getElementById('validationModal').classList.remove('flex');
    document.getElementById('validationForm').reset();
}
</script>
@endsection
